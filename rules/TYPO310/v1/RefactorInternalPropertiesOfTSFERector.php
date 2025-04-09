<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.1/Deprecation-89001-InternalPublicTSFEProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector\RefactorInternalPropertiesOfTSFERectorTest
 */
final class RefactorInternalPropertiesOfTSFERector extends AbstractRector
{
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

    public function __construct(Typo3NodeResolver $typo3NodeResolver, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor Internal public TSFE properties', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$domainStartPage = $GLOBALS['TSFE']->domainStartPage;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
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

        if (! $this->isNames($node->name, [self::HASH, 'domainStartPage'])) {
            return null;
        }

        if ($this->isName($node->name, self::HASH)) {
            return $this->refactorCacheHash();
        }

        return $this->refactorDomainStartPage();
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        return ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        ) && ! $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }

    private function refactorCacheHash(): Node
    {
        return new ArrayDimFetch($this->nodeFactory->createMethodCall(
            $this->createPageArguments(),
            'getArguments'
        ), new String_(self::HASH));
    }

    private function createPageArguments(): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->typo3GlobalsFactory->create('REQUEST'),
            'getAttribute',
            ['routing']
        );
    }

    private function refactorDomainStartPage(): Node
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(
                $this->typo3GlobalsFactory->create('REQUEST'),
                'getAttribute',
                ['site']
            ),
            'getRootPageId'
        );
    }
}
