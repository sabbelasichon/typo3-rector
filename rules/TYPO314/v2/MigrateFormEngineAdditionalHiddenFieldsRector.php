<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Reflection\ClassReflection;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-109102-FormEngineAdditionalHiddenFields.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateFormEngineAdditionalHiddenFieldsRector\MigrateFormEngineAdditionalHiddenFieldsRectorTest
 */
final class MigrateFormEngineAdditionalHiddenFieldsRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Migrate FormEngine result array key "additionalHiddenFields" to "html"', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$resultArray = $this->initializeResultArray();
$resultArray['additionalHiddenFields'][] = '<input type="hidden" name="myField" value="myValue" />';
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$resultArray = $this->initializeResultArray();
$resultArray['html'] .= '<input type="hidden" name="myField" value="myValue" />';
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        $assign = $node->expr;
        if (! $assign instanceof Assign) {
            return null;
        }

        $hiddenFieldDimFetch = $assign->var;
        if (! $hiddenFieldDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        $additionalHiddenFieldsDimFetch = $hiddenFieldDimFetch->var;
        if (! $additionalHiddenFieldsDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        if ($additionalHiddenFieldsDimFetch->dim === null) {
            return null;
        }

        if ($this->valueResolver->getValue($additionalHiddenFieldsDimFetch->dim) !== 'additionalHiddenFields') {
            return null;
        }

        $classReflection = ScopeFetcher::fetch($node)->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (! $classReflection->is('TYPO3\CMS\Backend\Form\NodeInterface')) {
            return null;
        }

        $node->expr = new Concat(
            new ArrayDimFetch($additionalHiddenFieldsDimFetch->var, new String_('html')),
            $assign->expr
        );

        return $node;
    }
}
