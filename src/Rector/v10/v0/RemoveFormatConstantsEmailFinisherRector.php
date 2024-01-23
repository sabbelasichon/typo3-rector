<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-87200-EmailFinisherFormatContants.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v4\RemoveFormatConstantsEmailFinisherRector\RemoveFormatConstantsEmailFinisherRectorTest
 */
final class RemoveFormatConstantsEmailFinisherRector extends AbstractRector
{
    /**
     * @var string
     */
    private const FORMAT_HTML = 'FORMAT_HTML';

    /**
     * @var string
     */
    private const FORMAT = 'format';

    /**
     * @var string
     */
    private const ADD_HTML_PART = 'addHtmlPart';

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Identical::class, Assign::class, ArrayItem::class];
    }

    /**
     * @param ClassConstFetch|MethodCall|Assign|ArrayItem|Identical $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorSetOptionMethodCall($node);
        }

        if ($node instanceof Assign) {
            return $this->refactorOptionAssignment($node);
        }

        if ($node instanceof ArrayItem) {
            return $this->refactorArrayItemOption($node);
        }

        return $this->refactorCondition($node);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove constants FORMAT_PLAINTEXT and FORMAT_HTML of class TYPO3\CMS\Form\Domain\Finishers\EmailFinisher',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$this->setOption('addHtmlPart', true);
CODE_SAMPLE
                ),
            ]
        );
    }

    private function refactorSetOptionMethodCall(MethodCall $methodCall): ?Node
    {
        if (! $this->isName($methodCall->name, 'setOption')) {
            return null;
        }

        if (! isset($methodCall->args[0])) {
            return null;
        }

        if (! $this->valueResolver->isValue($methodCall->args[0]->value, self::FORMAT)) {
            return null;
        }

        if (! isset($methodCall->args[1])) {
            return null;
        }

        $classConstFetch = $methodCall->args[1]->value;

        if (! $classConstFetch instanceof ClassConstFetch) {
            return null;
        }

        if ($this->shouldSkip($classConstFetch)) {
            return null;
        }

        $methodCall->args[0]->value = new String_(self::ADD_HTML_PART);
        $methodCall->args[1]->value = $this->nodeNameResolver->isName(
            $classConstFetch->name,
            self::FORMAT_HTML
        ) ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();

        return $methodCall;
    }

    private function refactorArrayItemOption(ArrayItem $arrayItem): ?Node
    {
        if (! $arrayItem->key instanceof Expr || ! $this->valueResolver->isValue($arrayItem->key, self::FORMAT)) {
            return null;
        }

        $classConstFetch = $arrayItem->value;

        if (! $classConstFetch instanceof ClassConstFetch) {
            return null;
        }

        if ($this->shouldSkip($classConstFetch)) {
            return null;
        }

        $addHtmlPart = $this->isName($classConstFetch->name, self::FORMAT_HTML);
        $arrayItem->key = new String_(self::ADD_HTML_PART);
        $arrayItem->value = $addHtmlPart ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();

        return $arrayItem;
    }

    private function refactorOptionAssignment(Assign $assign): ?Node
    {
        if (! $assign->var instanceof ArrayDimFetch) {
            return null;
        }

        if (! $this->isName($assign->var->var, 'options')) {
            return null;
        }

        if (! $assign->var->dim instanceof Expr || ! $this->valueResolver->isValue($assign->var->dim, self::FORMAT)) {
            return null;
        }

        $classConstFetch = $assign->expr;

        if (! $classConstFetch instanceof ClassConstFetch) {
            return null;
        }

        if ($this->shouldSkip($classConstFetch)) {
            return null;
        }

        $addHtmlPart = $this->isName($classConstFetch->name, self::FORMAT_HTML);
        $assign->var->dim = new String_(self::ADD_HTML_PART);
        $assign->expr = $addHtmlPart ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();

        return $assign;
    }

    private function refactorCondition(Identical $identical): ?Node
    {
        $arrayDimFetch = $identical->left instanceof ArrayDimFetch ? $identical->left : $identical->right;

        if (! $arrayDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        if (! $this->isName($arrayDimFetch->var, 'options')) {
            return null;
        }

        if (! $arrayDimFetch->dim instanceof Expr || ! $this->valueResolver->isValue(
            $arrayDimFetch->dim,
            self::FORMAT
        )) {
            return null;
        }

        $classConstFetch = $identical->right instanceof ClassConstFetch ? $identical->right : $identical->left;

        if (! $classConstFetch instanceof ClassConstFetch) {
            return null;
        }

        if ($this->shouldSkip($classConstFetch)) {
            return null;
        }

        $addHtmlPart = $this->isName($classConstFetch->name, self::FORMAT_HTML);
        $arrayDimFetch->dim = new String_(self::ADD_HTML_PART);

        $boolean = $addHtmlPart ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();

        if ($identical->right instanceof ClassConstFetch) {
            $identical->right = $boolean;
        } else {
            $identical->left = $boolean;
        }

        return $identical;
    }

    private function shouldSkip(ClassConstFetch $classConstFetch): bool
    {
        if (! $this->isObjectType(
            $classConstFetch->class,
            new ObjectType('TYPO3\\CMS\\Form\\Domain\\Finishers\\EmailFinisher')
        )) {
            return true;
        }

        if (! $this->isNames($classConstFetch->name, [self::FORMAT_HTML, 'FORMAT_PLAINTEXT'])) {
            return true;
        }

        return false;
    }
}
