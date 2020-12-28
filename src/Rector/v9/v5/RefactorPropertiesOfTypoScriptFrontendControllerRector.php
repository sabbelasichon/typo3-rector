<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

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
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-86047-TSFEPropertiesMethodsAndChangeVisibility.html
 */
final class RefactorPropertiesOfTypoScriptFrontendControllerRector extends AbstractRector
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
        if (! $this->isObjectType($node->var, TypoScriptFrontendController::class)
            && ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
                $node,
                Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
            )) {
            return null;
        }

        $parentNode = $node->getAttribute('parent');

        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return null;
        }

        if (! $this->isNames($node->name, ['ADMCMD_preview_BEUSER_uid', 'workspacePreview', 'loginAllowedInBranch'])) {
            return null;
        }

        if ($this->isName($node->name, 'loginAllowedInBranch')) {
            return $this->createMethodCall($node->var, 'checkIfLoginAllowedInBranch');
        }

        $contextInstanceNode = $this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(Context::class),
        ]);

        if ($this->isName($node->name, 'ADMCMD_preview_BEUSER_uid')) {
            return $this->createMethodCall($contextInstanceNode, 'getPropertyFromAspect', ['backend.user', 'id', 0]);
        }

        return $this->createMethodCall($contextInstanceNode, 'getPropertyFromAspect', ['workspace', 'id', 0]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor some properties of TypoScriptFrontendController', [
            new CodeSample(<<<'PHP'
$previewBeUserUid = $GLOBALS['TSFE']->ADMCMD_preview_BEUSER_uid;
$workspacePreview = $GLOBALS['TSFE']->workspacePreview;
$loginAllowedInBranch = $GLOBALS['TSFE']->loginAllowedInBranch;
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Context\Context;
$previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
$workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
$loginAllowedInBranch = $GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
PHP
            ),
        ]);
    }
}
