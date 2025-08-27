<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102621-MostTSFEMembersMarkedInternalOrRead-only.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerMethodCallsRector\MigrateTypoScriptFrontendControllerMethodCallsRectorTest
 */
final class MigrateTypoScriptFrontendControllerMethodCallsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @var array<string, array{attribute: string, method?: string}>
     */
    private const METHOD_CALL_TO_REQUEST_ATTRIBUTE_AND_METHOD = [
        'getRequestedId' => [
            'attribute' => 'routing',
            'method' => 'getPageId',
        ],
        'getSite' => [
            'attribute' => 'site',
        ],
        'getPageArguments' => [
            'attribute' => 'routing',
        ],
    ];

    private Typo3NodeResolver $typo3NodeResolver;

    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3NodeResolver $typo3NodeResolver, Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TypoScriptFrontendController method calls and use the request attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getRequestedId();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_REQUEST']->getAttribute('routing')->getPageId();
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getLanguage();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_REQUEST']->getAttribute('language') ?? $GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getDefaultLanguage();
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getSite();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_REQUEST']->getAttribute('site');
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getPageArguments();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_REQUEST']->getAttribute('routing');
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        $typo3Request = $this->getTYPO3RequestInScope(ScopeFetcher::fetch($node));

        if ($methodName === 'getLanguage') {
            // Build: $request->getAttribute('language')
            $languageAttributeCall = $this->nodeFactory->createMethodCall($typo3Request, 'getAttribute', [
                $this->nodeFactory->createArg('language'),
            ]);

            // Build: $request->getAttribute('site')
            $siteAttributeCall = $this->nodeFactory->createMethodCall($typo3Request, 'getAttribute', [
                $this->nodeFactory->createArg('site'),
            ]);

            // Build: ->getDefaultLanguage()
            $defaultLanguageCall = $this->nodeFactory->createMethodCall($siteAttributeCall, 'getDefaultLanguage');

            // Build the final expression with the null coalescing operator: ... ?? ...
            return new Coalesce($languageAttributeCall, $defaultLanguageCall);
        }

        if (! isset(self::METHOD_CALL_TO_REQUEST_ATTRIBUTE_AND_METHOD[$methodName])) {
            return null;
        }

        $config = self::METHOD_CALL_TO_REQUEST_ATTRIBUTE_AND_METHOD[$methodName];

        // Create the ->getAttribute('...') call
        $getAttributeCall = $this->nodeFactory->createMethodCall($typo3Request, 'getAttribute', [
            $this->nodeFactory->createArg($config['attribute']),
        ]);

        // If a subsequent method call is needed, chain it
        if (isset($config['method'])) {
            return $this->nodeFactory->createMethodCall($getAttributeCall, $config['method']);
        }

        return $getAttributeCall;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->isGlobals($methodCall)
            && ! $this->isObjectType(
                $methodCall->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            );
    }

    private function isGlobals(MethodCall $methodCall): bool
    {
        return $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    /**
     * @return ArrayDimFetch|PropertyFetch
     */
    private function getTYPO3RequestInScope(Scope $scope)
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            return $this->nodeFactory->createPropertyFetch('this', 'request');
        }

        return $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
    }
}
