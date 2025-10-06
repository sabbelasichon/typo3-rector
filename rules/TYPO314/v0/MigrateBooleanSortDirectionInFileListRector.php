<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107225-BooleanSortDirectionInFileListStart.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateBooleanSortDirectionInFileListRector\MigrateBooleanSortDirectionInFileListRectorTest
 */
final class MigrateBooleanSortDirectionInFileListRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate boolean sort direction in \TYPO3\CMS\Filelist\FileList->start()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$fileList->start($folder, $currentPage, $sortField, false, $mode);
$fileList->start($folder, $currentPage, $sortField, true, $mode);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$fileList->start($folder, $currentPage, $sortField, \TYPO3\CMS\Filelist\Type\SortDirection::ASCENDING, $mode);
$fileList->start($folder, $currentPage, $sortField, \TYPO3\CMS\Filelist\Type\SortDirection::DESCENDING, $mode);
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $args = $node->args;
        $sortDirectionArgument = $args[3]->value;

        if ($this->valueResolver->isFalse($sortDirectionArgument)) {
            $args[3]->value = $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Filelist\Type\SortDirection',
                'ASCENDING'
            );
            $node->args = $args;
            return $node;
        }

        if ($this->valueResolver->isTrue($sortDirectionArgument)) {
            $args[3]->value = $this->nodeFactory->createClassConstFetch(
                'TYPO3\CMS\Filelist\Type\SortDirection',
                'DESCENDING'
            );
            $node->args = $args;
            return $node;
        }

        return null;
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->isName($node->name, 'start')) {
            return true;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Filelist\FileList')
        )) {
            return true;
        }

        $args = $node->args;
        return count($args) < 4;
    }
}
