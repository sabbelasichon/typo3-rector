<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
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
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerConfigRootLineRector\MigrateTypoScriptFrontendControllerConfigRootLineRectorTest
 */
final class MigrateTypoScriptFrontendControllerConfigRootLineRector extends AbstractRector implements DocumentedRuleInterface
{
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
        return new RuleDefinition("Migrate `TypoScriptFrontendController->config['rootLine']`", [new CodeSample(
            <<<'CODE_SAMPLE'
$rootLine = $GLOBALS['TSFE']->config['rootLine'];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getLocalRootLine();
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ArrayDimFetch::class];
    }

    /**
     * @param ArrayDimFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $requestFetcherVariable = $this->createTYPO3RequestGetAttributeMethodCall($scope);

        return $this->nodeFactory->createMethodCall($requestFetcherVariable, 'getLocalRootLine');
    }

    private function shouldSkip(ArrayDimFetch $node): bool
    {
        if (! $node->var instanceof PropertyFetch) {
            return true;
        }

        if (! $this->isGlobals($node->var)
            && ! $this->isObjectType(
                $node->var->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            )
        ) {
            return true;
        }

        if (! $this->isName($node->var->name, 'config')) {
            return true;
        }

        return ! ($node->dim instanceof String_ && $node->dim->value === 'rootLine');
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function createTYPO3RequestGetAttributeMethodCall(Scope $scope): MethodCall
    {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection
            && $classReflection->is('TYPO3\CMS\Extbase\Mvc\Controller\ActionController')
        ) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        return $this->nodeFactory->createMethodCall(
            $requestFetcherVariable,
            'getAttribute',
            ['frontend.page.information']
        );
    }
}
