<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\StaticCall;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\GeneralUtilitySuperGlobalsToPsr7ServerRequestFactory;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Deprecation-100053-GeneralUtility_GP.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\MigrateGeneralUtilityGPRector\MigrateGeneralUtilityGPRectorTest
 */
final class MigrateGeneralUtilityGPRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Migrate `GeneralUtility::_GP()` to use PSR-7 ServerRequest instead', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$value = GeneralUtility::_GP('tx_scheduler');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$value = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $value = GeneralUtility::_GP('tx_scheduler');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $value = $this->request->getParsedBody()['tx_scheduler'] ?? $this->request->getQueryParams()['tx_scheduler'] ?? null;
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
    public function refactor(Node $node): ?Coalesce
    {
        $scope = ScopeFetcher::fetch($node);
        $getParsedBody = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
            $scope->getClassReflection(),
            $node,
            'getParsedBody',
            '_GP'
        );

        if (! $getParsedBody instanceof ArrayDimFetch) {
            return null;
        }

        $getQueryParams = $this->globalsToPsr7ServerRequestFactory->refactorToPsr7MethodCall(
            $scope->getClassReflection(),
            $node,
            'getQueryParams',
            '_GP'
        );

        if (! $getQueryParams instanceof ArrayDimFetch) {
            return null;
        }

        return new Coalesce($getParsedBody, new Coalesce($getQueryParams, $this->nodeFactory->createNull()));
    }
}
