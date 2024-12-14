<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-102763-ExtbaseHashService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector\MigrateExtbaseHashServiceToUseCoreHashServiceRectorTest
 */
final class MigrateExtbaseHashServiceToUseCoreHashServiceRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    public const ADDITIONAL_SECRET = 'additional-secret';

    private const ADDITIONAL_SECRET_DEFAULT = 'changeMe';

    private string $additionalSecret;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the class HashService from extbase to the one from TYPO3 core', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

$hashService = GeneralUtility::makeInstance(HashService::class);

$generatedHash = $hashService->generateHmac('123');
$isValidHash = $hashService->validateHmac('123', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$hashService = GeneralUtility::makeInstance(HashService::class);

$generatedHash = $hashService->hmac('123', 'changeMe');
$isValidHash = $hashService->validateHmac('123', 'changeMe', $generatedHash);

$stringWithAppendedHash = $hashService->appendHmac('123', 'changeMe');
$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash, 'changeMe');
CODE_SAMPLE
                ,
                [
                    self::ADDITIONAL_SECRET => 'myAdditionalSecret',
                ]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node)
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node->var,
            new ObjectType('TYPO3\\CMS\\Core\\Crypto\\HashService')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, ['hmac', 'validateHmac', 'appendHmac', 'validateAndStripHmac'])) {
            return null;
        }

        if (count($node->args) > 1 && ! $this->isName($node->name, 'validateHmac')) {
            return null;
        }

        if (count($node->args) > 2 && $this->isName($node->name, 'validateHmac')) {
            return null;
        }

        $additionalSecretArgument = $this->nodeFactory->createArg($this->additionalSecret);

        if ($this->isName($node->name, 'validateHmac')) {
            $node->args[2] = $node->args[1];
            $node->args[1] = $additionalSecretArgument;
        } else {
            $node->args[1] = $additionalSecretArgument;
        }

        return $node;
    }

    public function configure(array $configuration): void
    {
        $additionalSecret = $configuration[self::ADDITIONAL_SECRET] ?? self::ADDITIONAL_SECRET_DEFAULT;
        Assert::string($additionalSecret);
        $this->additionalSecret = $additionalSecret;
    }
}
