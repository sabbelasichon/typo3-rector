<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-88559-TSFE-sys_language_isocode.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector\UseTwoLetterIsoCodeFromSiteLanguageRectorTest
 */
final class UseTwoLetterIsoCodeFromSiteLanguageRector extends AbstractRector
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

        if (! $this->isName($node->name, 'sys_language_isocode')) {
            return null;
        }

        if ($this->isGlobals($node)) {
            return $this->createGetTwoLetterIsoCodeMethodCall($this->createTSFEGetTwoLetterIsoCodeMethodCall());
        }

        return $this->createGetTwoLetterIsoCodeMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'getLanguage')
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'The usage of the property sys_language_isocode is deprecated. Use method getTwoLetterIsoCode of SiteLanguage',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
if ($GLOBALS['TSFE']->sys_language_isocode) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
}
CODE_SAMPLE
                ),
            ]
        );
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        return ! $this->isGlobals($propertyFetch) && ! $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    private function createTSFEGetTwoLetterIsoCodeMethodCall(): MethodCall
    {
        return $this->nodeFactory->createMethodCall($this->typo3GlobalsFactory->create('TSFE'), 'getLanguage');
    }

    private function createGetTwoLetterIsoCodeMethodCall(MethodCall $methodCall): MethodCall
    {
        return $this->nodeFactory->createMethodCall($methodCall, 'getTwoLetterIsoCode');
    }
}
