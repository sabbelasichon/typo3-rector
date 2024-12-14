<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-101291-IntroduceCapabilitiesBitSet.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\IntroduceCapabilitiesBitSetRector\IntroduceCapabilitiesBitSetRectorTest
 */
final class IntroduceCapabilitiesBitSetRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Introduce capabilities bit set', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

echo ResourceStorageInterface::CAPABILITY_BROWSABLE;
echo ResourceStorageInterface::CAPABILITY_PUBLIC;
echo ResourceStorageInterface::CAPABILITY_WRITABLE;
echo ResourceStorageInterface::CAPABILITY_HIERARCHICAL_IDENTIFIERS;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Resource\Capabilities;
use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

echo Capabilities::CAPABILITY_BROWSABLE;
echo Capabilities::CAPABILITY_PUBLIC;
echo Capabilities::CAPABILITY_WRITABLE;
echo Capabilities::CAPABILITY_HIERARCHICAL_IDENTIFIERS;
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node)
    {
        return null;
    }
}
