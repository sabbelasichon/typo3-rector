<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73050-DeprecatedRandomGeneratorMethodsInGeneralUtility.html
 */
final class RandomMethodsToRandomClassRector extends AbstractRector
{
    /**
     * @var string
     */
    private const GENERATE_RANDOM_BYTES = 'generateRandomBytes';

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (! $this->isNames($node->name, [self::GENERATE_RANDOM_BYTES, 'getRandomHexString'])) {
            return null;
        }

        $randomClass = $this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->createClassConstReference(Random::class)]
        );

        if ($this->isName($node->name, self::GENERATE_RANDOM_BYTES)) {
            return $this->createMethodCall($randomClass, self::GENERATE_RANDOM_BYTES, $node->args);
        }

        return $this->createMethodCall($randomClass, 'generateRandomHexString', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Deprecated random generator methods in GeneralUtility', [new CodeSample(
            <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$randomBytes = GeneralUtility::generateRandomBytes();
$randomHex = GeneralUtility::getRandomHexString();
PHP
            ,
            <<<'PHP'
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes();
$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString();
PHP
        )]);
    }
}
