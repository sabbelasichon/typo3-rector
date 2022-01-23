<?php

declare (strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Backend\Shortcut\ShortcutRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.3/Deprecation-84993-DeprecateSomeTSconfigRelatedMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v3\BackendUtilityGetModTsConfigRector\BackendUtilityGetModTsConfigRectorTest
 */
final class BackendUtilityGetModTsConfigRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @param StaticCall $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        if (!$this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, new \PHPStan\Type\ObjectType('TYPO3\\CMS\\Backend\\Utility\\BackendUtility'))) {
            return null;
        }
        if (!$this->isName($node->name, 'getModTSconfig')) {
            return null;
        }
        /** @var Arg[] $currentArgs */
        $currentArgs = $node->args;
        $args[] = $currentArgs[0];

        // TODO Build ArrayDimFetch with variable 2 content
        $newPagesTsConfigKeys = explode('.', $this->valueResolver->getValue($node->args[1]->value));

        // TODO Read the original variable if given
        $configArrayVariable = new ArrayDimFetch(new Variable('configArray'), new String_('properties'));
        $configArrayNode = new Expression(
            new Assign(
                $configArrayVariable,
                new Coalesce(
                    $this->nodeFactory->createStaticCall(BackendUtility::class, 'getPagesTSconfig', $args),
                    $this->nodeFactory->createArray([])
                )
            )
        );

        // TODO Remov ethe original node
        $this->addNodeAfterNode($configArrayNode, $node);
        //$this->removeNode($node);

        return $node;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\StaticCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Migrate the method BackendUtility::getModTSconfig() to use BackendUtility::getPagesTSconfig()', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$configArray = BackendUtility::getModTSconfig($pid, 'mod.web_list');
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
$configArray['properties'] = BackendUtility::getPagesTSconfig($pid)['mod.']['web_list.'] ?? [];
CODE_SAMPLE
        )]);
    }
}
