<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-84994-BackendUtilitygetPidForModTSconfigDeprecated.html
 */
final class CopyMethodGetPidForModTSconfigRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(BackendUtility::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'getPidForModTSconfig')) {
            return null;
        }

        $tableVariableNode = $node->args[0]->value;
        if ($tableVariableNode instanceof String_) {
            $tableVariableNode = new Variable('table');
            $this->addNodeBeforeNode(new Assign($tableVariableNode, $node->args[0]->value), $node);
        }

        return new Ternary(new BooleanAnd(
            new Identical($tableVariableNode, new String_('pages')),
            $this->nodeFactory->createStaticCall(MathUtility::class, 'canBeInterpretedAsInteger', [$node->args[1]])
        ), $node->args[1]->value, $node->args[2]->value);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Copy method getPidForModTSconfig of class BackendUtility over', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;BackendUtility::getPidForModTSconfig('pages', 1, 2);
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\MathUtility;

$table = 'pages';
$uid = 1;
$pid = 2;
$table === 'pages' && MathUtility::canBeInterpretedAsInteger($uid) ? $uid : $pid;
CODE_SAMPLE
            ),
        ]);
    }
}
