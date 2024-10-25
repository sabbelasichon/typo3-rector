<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use Rector\Rector\AbstractScopeAwareRector;
use Ssch\TYPO3Rector\NodeFactory\GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory;
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
    private GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory $globalsToPsr7ServerRequestFactory;

    public function __construct(GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory $globalsToPsr7ServerRequestFactory)
    {
        $this->globalsToPsr7ServerRequestFactory = $globalsToPsr7ServerRequestFactory;
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
        return [Coalesce::class, StaticCall::class];
    }

    /**
     * @param Coalesce|StaticCall $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node instanceof Coalesce) {
            $staticCall = $node->left;

            if (! $staticCall instanceof StaticCall) {
                return null;
            }

            /** @var ArrayDimFetch $arrayDimFetch */
            $arrayDimFetch = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
                $scope->getClassReflection(),
                $staticCall,
                'getQueryParams',
                '_GET'
            );

            $node->left = $arrayDimFetch;
            return $node;
        }

        $methodCall = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
            $scope->getClassReflection(),
            $node,
            'getQueryParams',
            '_GET'
        );

        if ($methodCall instanceof MethodCall) {
            return $methodCall;
        }

        if (! $methodCall instanceof ArrayDimFetch) {
            return null;
        }

        return new Coalesce($methodCall, $this->nodeFactory->createNull());
    }
}
