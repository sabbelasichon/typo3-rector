<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Renaming;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector\ConstantsToBackedEnumValueRectorTest
 */
final class ConstantsToBackedEnumValueRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface, NoChangelogRequiredInterface, DocumentedRuleInterface
{
    /**
     * @var RenameClassAndConstFetch[]
     */
    private array $renameClassConstFetches = [];

    /**
     * @param array<int, RenameClassAndConstFetch> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, RenameClassAndConstFetch::class);
        $this->renameClassConstFetches = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate all FILETYPE_* constants from AbstractFile to FileType enum class', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\FileType::UNKNOWN;
CODE_SAMPLE
                ,
                [
                    new RenameClassAndConstFetch(
                        'TYPO3\CMS\Core\Imaging\Icon',
                        'SIZE_MEDIUM',
                        'TYPO3\CMS\Core\Imaging\IconSize',
                        'MEDIUM'
                    ),
                ]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /**
     * @param ClassConstFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->renameClassConstFetches as $renameClassConstFetch) {
            if (! $this->isName($node->name, $renameClassConstFetch->getOldConstant())) {
                continue;
            }

            if (! $this->isObjectType($node->class, $renameClassConstFetch->getOldObjectType())) {
                continue;
            }

            return $this->nodeFactory->createClassConstFetch(
                $renameClassConstFetch->getNewClass(),
                $renameClassConstFetch->getNewConstant()
            );
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
