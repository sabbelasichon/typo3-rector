<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractScopeAwareRector;
use Ssch\TYPO3Rector\NodeFactory\GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.2/Deprecation-99615-GeneralUtilityGPMerged.html#deprecation-99615-generalutility-gpmerged
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v2\MigrateGeneralUtilityGPMergedRector\MigrateGeneralUtilityGPMergedRectorTest
 */
final class MigrateGeneralUtilityGPMergedRector extends AbstractScopeAwareRector
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
        return new RuleDefinition('Migrate GeneralUtility::_GPmerged', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;

$getMergedWithPost = $request->getQueryParams()['tx_scheduler'];
ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $request->getParsedBody()['tx_scheduler']);
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $getMergedWithPost = $this->request->getQueryParams()['tx_scheduler'];
        ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $this->request->getParsedBody()['tx_scheduler']);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Expression[]|null
     */
    public function refactorWithScope(Node $node, Scope $scope): ?array
    {
        $assignNode = $node->expr;
        if (! $assignNode instanceof Assign) {
            return null;
        }

        $staticCall = $assignNode->expr;

        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\\CMS\\Core\\Utility\\GeneralUtility')
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, '_GPmerged')) {
            return null;
        }

        $getParsedBody = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
            $scope->getClassReflection(),
            $staticCall,
            'getQueryParams',
            '_GPmerged'
        );

        if (! $getParsedBody instanceof ArrayDimFetch) {
            return null;
        }

        $assignNode->expr = $getParsedBody;

        /** @var Variable|PropertyFetch|StaticPropertyFetch $variable */
        $variable = $assignNode->var;

        $arrayUtilityMergeRecursiveWithOverruleStatement = $this->generateArrayUtilityMergeRecursiveWithOverruleStatement(
            $variable,
            $scope,
            $staticCall
        );
        if (! $arrayUtilityMergeRecursiveWithOverruleStatement instanceof Expression) {
            return [$node];
        }

        return [$node, $arrayUtilityMergeRecursiveWithOverruleStatement];
    }

    /**
     * @param Variable|PropertyFetch|StaticPropertyFetch $variable
     */
    private function generateArrayUtilityMergeRecursiveWithOverruleStatement(
        $variable,
        Scope $scope,
        StaticCall $staticCall
    ): ?Expression {
        $arrayDimFetch = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
            $scope->getClassReflection(),
            $staticCall,
            'getParsedBody',
            '_GPmerged'
        );
        if (! $arrayDimFetch instanceof ArrayDimFetch) {
            return null;
        }

        return new Expression(
            $this->nodeFactory->createStaticCall('TYPO3\\CMS\\Core\\Utility\\ArrayUtility', 'mergeRecursiveWithOverrule', [
                $this->nodeFactory->createArg($variable),
                $this->nodeFactory->createArg($arrayDimFetch),
            ])
        );
    }
}
