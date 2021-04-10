<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Deprecation-73050-DeprecatedRandomGeneratorMethodsInGeneralUtility.html
 */
final class RandomMethodsToRandomClassRector extends AbstractRector
{
    /**
     * @var string
     */
    private const GENERATE_RANDOM_BYTES = 'generateRandomBytes';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(GeneralUtility::class)
        )) {
            return null;
        }

        if (! $this->isNames($node->name, [self::GENERATE_RANDOM_BYTES, 'getRandomHexString'])) {
            return null;
        }

        $randomClass = $this->nodeFactory->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [$this->nodeFactory->createClassConstReference(Random::class)]
        );

        if ($this->isName($node->name, self::GENERATE_RANDOM_BYTES)) {
            return $this->nodeFactory->createMethodCall($randomClass, self::GENERATE_RANDOM_BYTES, $node->args);
        }

        return $this->nodeFactory->createMethodCall($randomClass, 'generateRandomHexString', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Deprecated random generator methods in GeneralUtility', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$randomBytes = GeneralUtility::generateRandomBytes();
$randomHex = GeneralUtility::getRandomHexString();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Crypto\Random;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes();
$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString();
CODE_SAMPLE
        )]);
    }
}
