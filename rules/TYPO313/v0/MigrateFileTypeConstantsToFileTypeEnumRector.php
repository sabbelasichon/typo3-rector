<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-102032-AbstractFileConstants.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateFileTypeConstantsToFileTypeEnumRector\MigrateFileTypeConstantsToFileTypeEnumRectorTest
 */
final class MigrateFileTypeConstantsToFileTypeEnumRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate all FILETYPE_* constants from AbstractFile to FileType enum class', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Resource\FileType::UNKNOWN->value;
CODE_SAMPLE
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
        if (! $this->isObjectType($node->class, new ObjectType('TYPO3\CMS\Core\Resource\AbstractFile'))) {
            return null;
        }

        if (! $this->isNames(
            $node->name,
            [
                'FILETYPE_UNKNOWN',
                'FILETYPE_TEXT',
                'FILETYPE_IMAGE',
                'FILETYPE_AUDIO',
                'FILETYPE_VIDEO',
                'FILETYPE_APPLICATION',
            ]
        )) {
            return null;
        }

        $oldConstName = $this->getName($node->name);

        if ($oldConstName === null) {
            return null;
        }

        [, $enumConstName] = explode('_', $oldConstName, 2);
        $enumConstFetch = $this->nodeFactory->createClassConstFetch('TYPO3\CMS\Core\Resource\FileType', $enumConstName);

        return new PropertyFetch($enumConstFetch, 'value');
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }
}
