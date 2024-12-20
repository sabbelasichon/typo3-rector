<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100596-GeneralUtility_GET.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\UseServerRequestInsteadOfGeneralUtilityGetRector\UseServerRequestInsteadOfGeneralUtilityGetRectorTest
 */
final class UseServerRequestInsteadOfGeneralUtilityGetRector extends AbstractRector implements DocumentedRuleInterface
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
    public function refactor(Node $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
        if ($node instanceof Coalesce) {
            $staticCall = $node->left;

            if (! $staticCall instanceof StaticCall) {
                return null;
            }

            if ($this->shouldSkip($staticCall)) {
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

        if ($this->shouldSkip($node)) {
            return null;
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

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\GeneralUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticMethodCall->name, '_GET');
    }
}
