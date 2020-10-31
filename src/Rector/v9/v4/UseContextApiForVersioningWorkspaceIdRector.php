<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85556-PageRepository-versioningWorkspaceId.html
 */
final class UseContextApiForVersioningWorkspaceIdRector extends AbstractRector
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
        if (! $this->isObjectType(
            $node->var,
            PageRepository::class
        ) && ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node->var,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'versioningWorkspaceId')) {
            return null;
        }

        $parentNode = $node->getAttribute('parent');

        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return null;
        }

        return $this->createMethodCall($this->createStaticCall(GeneralUtility::class, 'makeInstance', [
            $this->createClassConstantReference(Context::class),
        ]), 'getPropertyFromAspect', ['workspace', 'id', 0]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use context API instead of versioningWorkspaceId', [
            new CodeSample(
                <<<'PHP'
$workspaceId = null;
$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
PHP
                ,
                <<<'PHP'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$workspaceId = null;
$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
PHP
            ),
        ]);
    }
}
