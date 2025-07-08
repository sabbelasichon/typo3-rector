<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Variable;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102113-RemovedLegacySettingGFXgdlib.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateLegacySettingGFXgdlibRector\MigrateLegacySettingGFXgdlibRectorTest
 */
final class MigrateLegacySettingGFXgdlibRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Migrate legacy setting `GFX/gdlib`', [new CodeSample(
            <<<'CODE_SAMPLE'
if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] === true) {
    // do something
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
if (class_exists(\GdImage::class)) {
    // do something
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [BinaryOp::class];
    }

    /**
     * @param BinaryOp $node
     */
    public function refactor(Node $node): ?Node
    {
        $isPositiveCheck = $node instanceof Identical || $node instanceof Equal;
        $isNegativeCheck = $node instanceof NotIdentical || $node instanceof NotEqual;

        if (! $isPositiveCheck && ! $isNegativeCheck) {
            return null;
        }

        if ($this->isLegacyGdlibSetting($node->left)) {
            $otherNode = $node->right;
        } elseif ($this->isLegacyGdlibSetting($node->right)) {
            $otherNode = $node->left;
        } else {
            return null;
        }

        // Check if the other side of the comparison is `true` or `false`
        $isComparedToTrue = $this->valueResolver->isTrue($otherNode);
        $isComparedToFalse = $this->valueResolver->isFalse($otherNode);

        if (! $isComparedToTrue && ! $isComparedToFalse) {
            return null;
        }

        $classExistsCall = $this->nodeFactory->createFuncCall(
            'class_exists',
            [$this->nodeFactory->createClassConstFetch('GdImage', 'class')]
        );

        if (($isPositiveCheck && $isComparedToTrue) || ($isNegativeCheck && $isComparedToFalse)) {
            return $classExistsCall;
        }

        return new BooleanNot($classExistsCall);
    }

    private function isLegacyGdlibSetting(Node $node): bool
    {
        if (! $node instanceof ArrayDimFetch) {
            return false;
        }

        if (! $node->dim instanceof Expr || ! $this->valueResolver->isValue($node->dim, 'gdlib')) {
            return false;
        }

        $gfxNode = $node->var;
        if (! $gfxNode instanceof ArrayDimFetch) {
            return false;
        }

        if (! $gfxNode->dim instanceof Expr || ! $this->valueResolver->isValue($gfxNode->dim, 'GFX')) {
            return false;
        }

        $confVarsNode = $gfxNode->var;
        if (! $confVarsNode instanceof ArrayDimFetch) {
            return false;
        }

        if (! $confVarsNode->dim instanceof Expr
            || ! $this->valueResolver->isValue($confVarsNode->dim, 'TYPO3_CONF_VARS')
        ) {
            return false;
        }

        $globalsNode = $confVarsNode->var;
        if (! $globalsNode instanceof Variable) {
            return false;
        }

        return $this->isName($globalsNode, 'GLOBALS');
    }
}
