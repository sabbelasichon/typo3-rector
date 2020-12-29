<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v1;

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
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Page\CacheHashCalculator;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.1/Deprecation-89001-InternalPublicTSFEProperties.html
 */
final class RefactorInternalPropertiesOfTSFERector extends AbstractRector
{
    /**
     * @var string
     */
    private const HASH = 'cHash';

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
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Internal public TSFE properties', [
            new CodeSample(<<<'PHP'
PHP
                , <<<'PHP'
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isNames($node->name, ['cHash_array', self::HASH, 'domainStartPage'])) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($parentNode instanceof Assign && $parentNode->var === $node) {
            return null;
        }

        if ($this->isName($node->name, 'cHash_array')) {
            return $this->refactorCacheHashArray($node);
        }

        if ($this->isName($node->name, self::HASH)) {
            return $this->refactorCacheHash();
        }

        return $this->refactorDomainStartPage();
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        return ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function initializeEmptyArray(): Node
    {
        return new Expression(
            new Assign(new Variable(self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS), $this->createArray([]))
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
                $this->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getDynamicArguments')
            )
        );
    }

    private function getRelevantParametersFromCacheHashCalculator(): Node
    {
        $ifNode = new If_(
            new BooleanAnd(
                new BooleanNot(new Empty_(new Variable(self::QUERY_PARAMS))),
                new Coalesce(
                    new ArrayDimFetch(
                        $this->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getArguments'),
                        new String_(self::HASH)
                    ),
                    $this->createFalse()
                )
            )
        );
        // $relevantParametersForCachingFromPageArguments = GeneralUtility::makeInstance(CacheHashCalculator::class)->getRelevantParameters(HttpUtility::buildQueryString($queryParams));
        $ifNode->stmts[] = new Expression(
            new Assign(
                new ArrayDimFetch(new Variable(self::QUERY_PARAMS), new String_('id')),
                $this->createMethodCall(new Variable(self::PAGE_ARGUMENTS), 'getPageId')
            )
        );
        $ifNode->stmts[] = new Expression(
            new Assign(
                new Variable(self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS),
                $this->createMethodCall($this->createStaticCall(
                    GeneralUtility::class, 'makeInstance', [
                        $this->createClassConstantReference(CacheHashCalculator::class),
                    ]
                ), 'getRelevantParameters', [
                    $this->createStaticCall(HttpUtility::class, 'buildQueryString', [new Variable(self::QUERY_PARAMS)]),
                ])
            )
        );

        return $ifNode;
    }

    private function refactorCacheHashArray(PropertyFetch $node): Node
    {
        $currentStmts = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmts ?? $node;
        $this->addNodeBeforeNode($this->initializeEmptyArray(), $positionNode);
        $this->addNodeBeforeNode($this->initializePageArguments(), $positionNode);
        $this->addNodeBeforeNode($this->initializeQueryParams(), $positionNode);
        $this->addNodeBeforeNode($this->getRelevantParametersFromCacheHashCalculator(), $positionNode);

        return new Variable(self::RELEVANT_PARAMETERS_FOR_CACHING_FROM_PAGE_ARGUMENTS);
    }

    private function refactorCacheHash(): Node
    {
        return new ArrayDimFetch($this->createMethodCall($this->createPageArguments(), 'getArguments'), new String_(
            self::HASH
        ));
    }

    private function createPageArguments(): MethodCall
    {
        return $this->createMethodCall(
            new ArrayDimFetch(new Variable('GLOBALS'), new String_('REQUEST')), 'getAttribute', ['routing']
        );
    }

    private function refactorDomainStartPage(): Node
    {
        return $this->createMethodCall($this->createMethodCall(
            new ArrayDimFetch(new Variable('GLOBALS'), new String_('REQUEST')), 'getAttribute', ['site']
        ), 'getRootPageId');
    }
}
