<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\General\Renaming;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
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
        return new RuleDefinition('Migrate constants to enum class values', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\FileType::UNKNOWN->value
\TYPO3\CMS\Core\Resource\FileType::TEXT->value
\TYPO3\CMS\Core\Resource\FileType::IMAGE->value
\TYPO3\CMS\Core\Resource\FileType::AUDIO->value
\TYPO3\CMS\Core\Resource\FileType::VIDEO->value
\TYPO3\CMS\Core\Resource\FileType::APPLICATION->value
CODE_SAMPLE
                ,
                [
                    new RenameClassAndConstFetch(
                        'TYPO3\CMS\Core\Resource\AbstractFile',
                        'FILETYPE_UNKNOWN',
                        'TYPO3\CMS\Core\Resource\FileType',
                        'UNKNOWN'
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

            $enumConstFetch = $this->nodeFactory->createClassConstFetch(
                $renameClassConstFetch->getNewClass(),
                $renameClassConstFetch->getNewConstant()
            );

            return new PropertyFetch($enumConstFetch, 'value');
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
