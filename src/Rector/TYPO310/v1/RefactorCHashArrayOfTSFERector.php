<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\TYPO310\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.1/Deprecation-89001-InternalPublicTSFEProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v1\typo3\RefactorCHashArrayOfTSFERector\RefactorCHashArrayOfTSFERectorTest
 */
final class RefactorCHashArrayOfTSFERector extends AbstractRector
{
    /**
     * @var string
     */
    private const RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS = 'relevantParametersForCachingFromPageArguments';

    /**
     * @var string
     */
    private const PAGE_ARGUMENTS = 'pageArguments';

    /**
     * @var string
     */
    private const QUERY_PARAMS = 'queryParams';

    /**
     * @var string
     */
    private const HASH = 'cHash';

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(
        Typo3NodeResolver $typo3NodeResolver,
        Typo3GlobalsFactory $typo3GlobalsFactory
    ) {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $assignNode = $node->expr;

        if (! $assignNode instanceof Assign) {
            return null;
        }

        $propertyFetch = $assignNode->expr;

        if (! $propertyFetch instanceof PropertyFetch) {
            return null;
        }

        if (! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return null;
        }

        return [
            $this->initializeEmptyArray(),
            $this->initializePageArguments(),
            $this->initializeQueryParams(),
            $this->getRelevantParametersFromCacheHashCalculator(),
            new Expression(new Assign($assignNode->var, new Variable(
                self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS
            ))),
        ];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Internal public property cHash_array', [new CodeSample(
            <<<'CODE_SAMPLE'
$cHash_array = $GLOBALS['TSFE']->cHash_array;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;

$relevantParametersForCachingFromPageArguments = [];
$pageArguments = $GLOBALS['REQUEST']->getAttribute('routing');
$queryParams = $pageArguments->getDynamicArguments();
if (!empty($queryParams) && ($pageArguments->getArguments()['cHash'] ?? false)) {
    $queryParams['id'] = $pageArguments->getPageId();
    $relevantParametersForCachingFromPageArguments = GeneralUtility::makeInstance(CacheHashCalculator::class)->getRelevantParameters(HttpUtility::buildQueryString($queryParams));
}
$cHash_array = $relevantParametersForCachingFromPageArguments;
CODE_SAMPLE
        )]);
    }

    private function initializeEmptyArray(): Node
    {
        return new Expression(
            new Assign(new Variable(
                self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS
            ), $this->nodeFactory->createArray([]))
        );
    }

    private function initializePageArguments(): Node
    {
        return new Expression(new Assign(new Variable(self::PAGE_ARGUMENTS), $this->createPageArguments()));
    }

    private function initializeQueryParams(): Node
    {
        return new Expression(
            new Assign(
                new Variable(self::QUERY_PARAMS),
                $this->nodeFactory->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getDynamicArguments')
            )
        );
    }

    private function getRelevantParametersFromCacheHashCalculator(): Node
    {
        $if = new If_(
            new BooleanAnd(
                new BooleanNot(new Empty_(new Variable(self::QUERY_PARAMS))),
                new Coalesce(
                    new ArrayDimFetch(
                        $this->nodeFactory->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getArguments'),
                        new String_(self::HASH)
                    ),
                    $this->nodeFactory->createFalse()
                )
            )
        );
        $if->stmts[] = new Expression(
            new Assign(
                new ArrayDimFetch(new Variable(self::QUERY_PARAMS), new String_('id')),
                $this->nodeFactory->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getPageId')
            )
        );
        $if->stmts[] = new Expression(
            new Assign(
                new Variable(self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS),
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                        $this->nodeFactory->createClassConstReference('TYPO3\CMS\Frontend\Page\CacheHashCalculator'),
                    ]),
                    'getRelevantParameters',
                    [
                        $this->nodeFactory->createStaticCall(
                            'TYPO3\CMS\Core\Utility\HttpUtility',
                            'buildQueryString',
                            [new Variable(self::QUERY_PARAMS)]
                        ),
                    ]
                )
            )
        );

        return $if;
    }

    private function createPageArguments(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->typo3GlobalsFactory->create('REQUEST'),
            'getAttribute',
            ['routing']
        );
    }
}
