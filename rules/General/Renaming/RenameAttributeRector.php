<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Renaming;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Ssch\TYPO3Rector\General\Renaming\ValueObject\RenameAttribute;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RenameAttributeRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface, NoChangelogRequiredInterface
{
    /**
     * @var RenameAttribute[]
     */
    private array $renameAttributes = [];

    /**
     * @param array<int, RenameAttribute> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RenameAttribute::class);
        $this->renameAttributes = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename Attribute', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
#[Controller]
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[AsController]
CODE_SAMPLE
                ,
                [
                    new RenameAttribute(
                        'TYPO3\CMS\Backend\Attribute\Controller',
                        'TYPO3\CMS\Backend\Attribute\AsController',
                    ),
                ]
            ),
        ]);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    public function getNodeTypes(): array
    {
        return [Node\Attribute::class];
    }

    /**
     * @param Node\Attribute $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->renameAttributes as $renameAttribute) {
            if (! $this->isName($node->name, $renameAttribute->getOldAttribute())) {
                continue;
            }

            $node->name = new FullyQualified($renameAttribute->getNewAttribute());
        }

        return null;
    }
}
