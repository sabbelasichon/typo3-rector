<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-88559-TSFE-sys_language_isocode.html
 */
final class UseTwoLetterIsoCodeFromSiteLanguageRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
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
        if (! $this->isObjectType(
                $node,
                new ObjectType(TypoScriptFrontendController::class)
            ) && ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
                $node,
                Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
            )) {
            return null;
        }

        if (! $this->isName($node->name, 'sys_language_isocode')) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'getLanguage'),
            'getTwoLetterIsoCode'
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'The usage of the propery sys_language_isocode is deprecated. Use method getTwoLetterIsoCode of SiteLanguage',
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
}
