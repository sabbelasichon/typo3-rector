<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.4/Deprecation-68074-DeprecateGetPageRenderer.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v4\InstantiatePageRendererExplicitlyRector\InstantiatePageRendererExplicitlyRectorTest
 */
final class InstantiatePageRendererExplicitlyRector extends AbstractRector
{
    public function __construct(
        private Typo3NodeResolver $typo3NodeResolver
    ) {
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
                '$pageRenderer = $GLOBALS[\'TSFE\']->getPageRenderer();',
                '$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);'
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Controller\BackendController')
        )) {
            return false;
        }

        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Template\DocumentTemplate')
        )) {
            return false;
        }

        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
