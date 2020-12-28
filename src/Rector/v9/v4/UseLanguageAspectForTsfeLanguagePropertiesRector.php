<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85543-Language-relatedPropertiesInTypoScriptFrontendControllerAndPageRepository.html
 */
final class UseLanguageAspectForTsfeLanguagePropertiesRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
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

        if (! $this->isNames(
            $node->name,
            ['sys_language_uid', 'sys_language_content', 'sys_language_contentOL', 'sys_language_mode']
        )) {
            return null;
        }

        $parentNode = $node->getAttribute('parent');
        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return null;
        }

        $methodCall = null;

        switch ($this->getName($node->name)) {
            case 'sys_language_uid':
                $methodCall = 'getId';
                break;
            case 'sys_language_content':
                $methodCall = 'getContentId';
                break;
            case 'sys_language_contentOL':
                $methodCall = 'getLegacyOverlayType';
                break;
            case 'sys_language_mode':
                $methodCall = 'getLegacyLanguageMode';
                break;
        }

        if (null === $methodCall) {
            return null;
        }

        return $this->createMethodCall(
            $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->createClassConstantReference(Context::class),
            ]), 'getAspect', ['language']),
            $methodCall
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use LanguageAspect instead of language properties of TSFE', [
            new CodeSample(
                <<<'PHP'
$languageUid = $GLOBALS['TSFE']->sys_language_uid;
PHP
                ,
                <<<'PHP'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$languageUid = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
PHP
            ),
        ]);
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if ($this->isObjectType($node->var, TypoScriptFrontendController::class)) {
            return false;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnParentVariableOfTypeTypoScriptFrontendController($node);
    }
}
