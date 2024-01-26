<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85878-EidUtilityAndVariousTSFEMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\RemoveMethodsFromEidUtilityAndTsfeRector\RemoveMethodsFromEidUtilityAndTsfeRectorTest
 */
final class RemoveMethodsFromEidUtilityAndTsfeRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     * @return int|null|Node
     */
    public function refactor(Node $node)
    {
        $staticOrMethodCall = $node->expr;

        if (! $staticOrMethodCall instanceof StaticCall && ! $staticOrMethodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($staticOrMethodCall)) {
            return null;
        }

        if ($this->isEidUtilityMethodCall($staticOrMethodCall)) {
            return NodeTraverser::REMOVE_NODE;
        }

        if (! $this->isNames(
            $staticOrMethodCall->name,
            [
                'initFEuser',
                'storeSessionData',
                'previewInfo',
                'hook_eofe',
                'addTempContentHttpHeaders',
                'sendCacheHeaders',
            ]
        )) {
            return null;
        }

        if ($this->isName($staticOrMethodCall->name, 'storeSessionData') && $staticOrMethodCall instanceof MethodCall) {
            return $this->delegateToFrontendUserProperty($staticOrMethodCall);
        }

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove EidUtility and various TSFE methods', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Frontend\Utility\EidUtility;

EidUtility::initExtensionTCA('foo');
EidUtility::initFeUser();
EidUtility::initLanguage();
EidUtility::initTCA();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
-
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function shouldSkip($call): bool
    {
        if ($this->isEidUtilityMethodCall($call)) {
            return false;
        }

        return ! $this->isMethodCallOnTsfe($call);
    }

    /**
     * @param StaticCall|MethodCall $call
     */
    private function isEidUtilityMethodCall($call): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $call,
            new ObjectType('TYPO3\CMS\Frontend\Utility\EidUtility')
        );
    }

    private function isMethodCallOnTsfe(Node $node): bool
    {
        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return true;
        }

        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }

    private function delegateToFrontendUserProperty(MethodCall $methodCall): MethodCall
    {
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createPropertyFetch($methodCall->var, 'fe_user'),
            (string) $this->getName($methodCall->name)
        );
    }
}
