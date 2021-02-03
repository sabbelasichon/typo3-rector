<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.4/Deprecation-68074-DeprecateGetPageRenderer.html
 */
final class InstantiatePageRendererExplicitlyRector extends AbstractRector
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

        if (! $this->isName($node->name, 'getPageRenderer')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->nodeFactory->createClassConstReference(PageRenderer::class),
        ]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Instantiate PageRenderer explicitly', [
            new CodeSample(
                '$pageRenderer = $GLOBALS[\'TSFE\']->getPageRenderer();', '$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);'
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->isMethodStaticCallOrClassMethodObjectType($node, BackendController::class)) {
            return false;
        }

        if ($this->isMethodStaticCallOrClassMethodObjectType($node, DocumentTemplate::class)) {
            return false;
        }

        if ($this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class)) {
            return false;
        }

        return ! $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
