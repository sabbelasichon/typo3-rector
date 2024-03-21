<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractScopeAwareRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100596-GeneralUtility_GET.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\UseServerRequestInsteadOfGeneralUtilityGetRectorTest
 */
final class UseServerRequestInsteadOfGeneralUtilityGetRector extends AbstractScopeAwareRector
{
    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(Typo3GlobalsFactory $typo3GlobalsFactory, ValueResolver $valueResolver)
    {
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use PSR-7 ServerRequest instead of GeneralUtility::_GET()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
$value = GeneralUtility::_GET('tx_scheduler');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
$value = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
class MyActionController extends ActionController
{
    public function myMethod()
    {
        $value = GeneralUtility::_GET('tx_scheduler');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
class MyActionController extends ActionController
{
    public function myMethod()
    {
        $value = $this->request->getQueryParams()['tx_scheduler'] ?? null;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->isName($node->name, '_GET')) {
            return null;
        }

        if (! isset($node->args[0])) {
            return null;
        }

        if ($this->valueResolver->isNull($node->args[0]->value)) {
            return null;
        }

        $classReflection = $scope->getClassReflection();

        if ($classReflection !== null && $classReflection->isSubclassOf(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController'
        )) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        $methodCall = new Node\Expr\ArrayDimFetch(
            $this->nodeFactory->createMethodCall($requestFetcherVariable, 'getQueryParams'),
            $node->args[0]->value
        );

        return new Coalesce($methodCall, $this->nodeFactory->createNull());
    }
}
