<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v1;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.1/Deprecation-99020-DeprecateTypoScriptTemplateService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector\TemplateServiceToServerRequestFrontendTypoScriptAttributeRectorTest
 */
final class TemplateServiceToServerRequestFrontendTypoScriptAttributeRector extends AbstractRector
{
    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3GlobalsFactory $typo3GlobalsFactory, Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TemplateService to ServerRequest frontend.typsocript attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$setup = $GLOBALS['TSFE']->tmpl->setup;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$setup = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();
CODE_SAMPLE
            ),
        ]);
    }

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

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $classReflection->isSubclassOf(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController'
        )) {
            $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');
        } else {
            $requestFetcherVariable = $this->typo3GlobalsFactory->create('TYPO3_REQUEST');
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($requestFetcherVariable, 'getAttribute', ['frontend.typoscript']),
            'getSetupArray'
        );
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if (! $this->isName($node->name, 'setup')) {
            return true;
        }

        if ($this->isOnTSFEGlobals($node)) {
            return false;
        }

        return ! $this->nodeTypeResolver->isObjectType(
            $node->var,
            new ObjectType('TYPO3\CMS\Core\TypoScript\TemplateService')
        );
    }

    private function isOnTSFEGlobals(PropertyFetch $node): bool
    {
        if (! $node->var instanceof PropertyFetch) {
            return false;
        }

        if (! $this->isName($node->var->name, 'tmpl')) {
            return false;
        }

        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals($node->var, 'TSFE');
    }
}
