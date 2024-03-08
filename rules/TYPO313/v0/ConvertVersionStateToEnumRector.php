<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\Switch_;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-101175-ConvertVersionStateToNativeEnum.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\ConvertVersionStateToEnumRector\ConvertVersionStateToEnumRectorTest
 */
final class ConvertVersionStateToEnumRector extends AbstractRector implements DocumentedRuleInterface
{
    private const VERSION_STATE_CLASS = 'TYPO3\CMS\Core\Versioning\VersionState';

    private const VERSION_STATE_CONSTANTS = [
        'DEFAULT_STATE',
        'NEW_PLACEHOLDER',
        'DELETE_PLACEHOLDER',
        'MOVE_POINTER',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert usages of TYPO3\CMS\Core\Versioning\VersionState to its Enum equivalent',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Versioning\VersionState;

class MyClass
{
    public function foo(): void
    {
        $type1 = VersionState::DEFAULT_STATE;
        $type2 = VersionState::NEW_PLACEHOLDER;
        $type3 = VersionState::DELETE_PLACEHOLDER;
        $type4 = VersionState::MOVE_POINTER;

        $versionState = VersionState::cast($row['t3ver_state']);
        if ($versionState->equals(VersionState::DELETE_PLACEHOLDER)) {
            // do something
        }
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Versioning\VersionState;

class MyClass
{
    public function foo(): void
    {
        $type1 = VersionState::DEFAULT_STATE->value;
        $type2 = VersionState::NEW_PLACEHOLDER->value;
        $type3 = VersionState::DELETE_PLACEHOLDER->value;
        $type4 = VersionState::MOVE_POINTER->value;

        $versionState = VersionState::tryFrom($row['t3ver_state'] ?? 0);
        if ($versionState === VersionState::DELETE_PLACEHOLDER) {
            // do something
        }
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [
            Assign::class,
            Arg::class,
            StaticCall::class,
            MethodCall::class,
            BinaryOp::class,
            BooleanNot::class,
            Switch_::class,
            Case_::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        // Handle direct assignments and function/method args
        if ($node instanceof Assign || $node instanceof Arg) {
            $expr = $node instanceof Assign ? $node->expr : $node->value;

            // 1) bare constant → append ->value
            if ($expr instanceof ClassConstFetch && $this->isMatchingVersionStateConstant($expr)) {
                if ($node instanceof Assign) {
                    $node->expr = new PropertyFetch($expr, 'value');
                } else {
                    $node->value = new PropertyFetch($expr, 'value');
                }

                return $node;
            }

            // 2) un‐cast new enum in arg/assign → extract inner constant’s ->value
            if ($expr instanceof New_
                && $this->isName($expr->class, self::VERSION_STATE_CLASS)
            ) {
                $args = $expr->getArgs();
                if (isset($args[0])
                    && $args[0]->value instanceof ClassConstFetch
                    && $this->isMatchingVersionStateConstant($args[0]->value)
                ) {
                    if ($node instanceof Assign) {
                        $node->expr = new PropertyFetch($args[0]->value, 'value');
                    } else {
                        $node->value = new PropertyFetch($args[0]->value, 'value');
                    }

                    return $node;
                }
            }

            // Case: new VersionState($expr)
            if ($expr instanceof New_ && $this->isName($expr->class, self::VERSION_STATE_CLASS)) {
                $args = $expr->getArgs();
                if (isset($args[0])) {
                    $value = $args[0]->value;
                    $safeValue = $value instanceof ArrayDimFetch && ! $this->isAlreadyCoalesced($value)
                        ? new Coalesce($value, new LNumber(0))
                        : $value;

                    if ($node instanceof Assign) {
                        $node->expr = new StaticCall(new FullyQualified(self::VERSION_STATE_CLASS), 'tryFrom', [
                            new Arg($safeValue),
                        ]);
                    } else {
                        $node->value = new StaticCall(new FullyQualified(self::VERSION_STATE_CLASS), 'tryFrom', [
                            new Arg($safeValue),
                        ]);
                    }

                    return $node;
                }
            }

            // Case: VersionState::CONST
            if ($expr instanceof ClassConstFetch && $this->isMatchingVersionStateConstant($expr)) {
                if ($node instanceof Assign) {
                    $node->expr = new PropertyFetch($expr, 'value');
                } else {
                    $node->value = new PropertyFetch($expr, 'value');
                }

                return $node;
            }

            // Case: (string)new VersionState(VersionState::CONST)
            if ($expr instanceof Expr\Cast\String_ && $expr->expr instanceof New_) {
                $new = $expr->expr;
                $args = $new->getArgs();
                if (count($args) === 1 && $args[0]->value instanceof ClassConstFetch
                    && $this->isMatchingVersionStateConstant($args[0]->value)
                ) {
                    if ($node instanceof Assign) {
                        $node->expr = new PropertyFetch($args[0]->value, 'value');
                    } else {
                        $node->value = new PropertyFetch($args[0]->value, 'value');
                    }

                    return $node;
                }
            }

            // Case: (string)(cond ? new VersionState(...) : new VersionState(...))
            if ($expr instanceof Expr\Cast\String_ && $expr->expr instanceof Ternary) {
                $ternary = $expr->expr;
                $cond = $ternary->cond;
                $ifExpr = $ternary->if;
                $elseExpr = $ternary->else;
                $transform = function ($branch) {
                    if ($branch instanceof New_) {
                        $args = $branch->getArgs();
                        if (count($args) === 1 && $args[0]->value instanceof ClassConstFetch
                            && $this->isMatchingVersionStateConstant($args[0]->value)
                        ) {
                            return new PropertyFetch($args[0]->value, 'value');
                        }
                    }

                    return null;
                };
                $newIf = $transform($ifExpr);
                $newElse = $transform($elseExpr);
                if ($newIf && $newElse) {
                    if ($node instanceof Assign) {
                        $node->expr = new Ternary($cond, $newIf, $newElse);
                    } else {
                        $node->value = new Ternary($cond, $newIf, $newElse);
                    }

                    return $node;
                }
            }

            return null;
        }

        if ($node instanceof ClassConstFetch) {
            return $this->refactorClassConstFetch($node);
        }

        if ($node instanceof StaticCall) {
            return $this->refactorStaticCall($node);
        }

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        if ($node instanceof BinaryOp) {
            return $this->refactorBinaryOp($node);
        }

        if ($node instanceof BooleanNot) {
            return $this->refactorBooleanNot($node);
        }

        if ($node instanceof Switch_) {
            return $this->refactorSwitch($node);
        }

        if ($node instanceof Case_) {
            return $this->refactorCase($node);
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ENUM;
    }

    private function refactorClassConstFetch(ClassConstFetch $classConstFetch): ?PropertyFetch
    {
        if (! $this->isMatchingVersionStateConstant($classConstFetch)) {
            return null;
        }

        return new PropertyFetch($classConstFetch, 'value');
    }

    private function refactorStaticCall(StaticCall $staticCall): ?StaticCall
    {
        if (! $this->isName($staticCall->class, self::VERSION_STATE_CLASS)
            || ! $this->isName($staticCall->name, 'cast')) {
            return null;
        }

        $staticCall->name = new Identifier('tryFrom');
        $args = $staticCall->getArgs();
        if (isset($args[0]) && $args[0]->value instanceof Expr) {
            $val = $args[0]->value;
            if ($val instanceof ArrayDimFetch && ! $this->isAlreadyCoalesced($val)) {
                $args[0]->value = new Coalesce($val, new LNumber(0));
            }
        }

        return $staticCall;
    }

    private function refactorMethodCall(MethodCall $call): ?Identical
    {
        if (! $this->isName($call->name, 'equals')) {
            return null;
        }

        $args = $call->getArgs();
        if (count($args) !== 1 || ! $args[0]->value instanceof ClassConstFetch) {
            return null;
        }

        $constFetch = $args[0]->value;
        if (! $this->isMatchingVersionStateConstant($constFetch)) {
            return null;
        }

        // $enum->equals(CONST) => $enum === CONST
        return new Identical($call->var, $constFetch);
    }

    private function refactorBinaryOp(BinaryOp $op): ?BinaryOp
    {
        if (! $op instanceof Identical && ! $op instanceof NotIdentical) {
            return null;
        }

        // Handle (int)($expr ?? 0) === VersionState::CONST
        $classConstFetchIsOnRightSide = false;
        if ($op->left instanceof ClassConstFetch && $this->isMatchingVersionStateConstant($op->left)) {
            $exprSide = $op->right;
        } elseif ($op->right instanceof ClassConstFetch && $this->isMatchingVersionStateConstant($op->right)) {
            $exprSide = $op->left;
            $classConstFetchIsOnRightSide = true;
        } else {
            return null;
        }

        if ($exprSide instanceof Int_) {
            $inner = $exprSide->expr;
            if (($inner instanceof ArrayDimFetch || (! $inner instanceof String_ && ! $inner instanceof LNumber))
                && ! $this->isAlreadyCoalesced($inner)
            ) {
                $inner = new Coalesce($inner, new LNumber(0));
            }

            $newCall = new StaticCall(new FullyQualified(self::VERSION_STATE_CLASS), 'tryFrom', [new Arg($inner)]);
            if ($classConstFetchIsOnRightSide) {
                $op->left = $newCall;
            } else {
                $op->right = $newCall;
            }

            return $op;
        }

        return null;
    }

    private function refactorBooleanNot(BooleanNot $not): ?NotIdentical
    {
        if (! $not->expr instanceof MethodCall) {
            return null;
        }

        $identical = $this->refactorMethodCall($not->expr);
        if ($identical instanceof Identical) {
            // ! $enum->equals(CONST) => $enum !== CONST
            return new NotIdentical($identical->left, $identical->right);
        }

        return null;
    }

    private function refactorSwitch(Switch_ $switch): ?Switch_
    {
        if (! $switch->cond instanceof StaticCall) {
            return null;
        }

        $sc = $switch->cond;
        if (! $this->isName($sc->class, self::VERSION_STATE_CLASS)
            || ! $this->isName($sc->name, 'cast')) {
            return null;
        }

        $newCond = $this->refactorStaticCall($sc);
        if ($newCond instanceof StaticCall) {
            $switch->cond = $newCond;
            return $switch;
        }

        return null;
    }

    private function refactorCase(Case_ $case): ?Case_
    {
        if (! $case->cond instanceof New_) {
            return null;
        }

        $newExpr = $case->cond;
        if (! $this->isName($newExpr->class, self::VERSION_STATE_CLASS)) {
            return null;
        }

        $args = $newExpr->getArgs();
        if (count($args) !== 1 || ! $args[0]->value instanceof ClassConstFetch) {
            return null;
        }

        $constFetch = $args[0]->value;
        if (! $this->isMatchingVersionStateConstant($constFetch)) {
            return null;
        }

        // new VersionState(CONST) => CONST
        $case->cond = $constFetch;
        return $case;
    }

    private function isMatchingVersionStateConstant(Expr $expr): bool
    {
        if (! $expr instanceof ClassConstFetch) {
            return false;
        }

        return $this->isName($expr->class, self::VERSION_STATE_CLASS)
            && $expr->name instanceof Identifier
            && in_array($expr->name->toString(), self::VERSION_STATE_CONSTANTS, true);
    }

    private function isAlreadyCoalesced(Expr $expr): bool
    {
        return $expr instanceof Coalesce;
    }
}
