<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85556-PageRepository-versioningWorkspaceId.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector\UseContextApiForVersioningWorkspaceIdRectorTest
 */
final class UseContextApiForVersioningWorkspaceIdRector extends AbstractRector
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
        return [Assign::class, Node\Stmt\Return_::class];
    }

    /**
     * @param Assign|Node\Stmt\Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $propertyFetch = $node->expr;

        if (! $propertyFetch instanceof PropertyFetch) {
            return null;
        }

        if ($this->shouldSkip($propertyFetch)) {
            return null;
        }

        if (! $this->isName($propertyFetch->name, 'versioningWorkspaceId')) {
            return null;
        }

        // Check if we have an assigment to the property, if so do not change it
        if ($node instanceof Assign && $node->var instanceof PropertyFetch) {
            return null;
        }

        $node->expr = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Context\Context'),
            ]),
            'getPropertyFromAspect',
            ['workspace', 'id', 0]
        );

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use context API instead of versioningWorkspaceId', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$workspaceId = null;
$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$workspaceId = null;
$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

$GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        $propertyFetch->var->setAttribute(
            AttributeKey::PHP_DOC_INFO,
            $propertyFetch->getAttribute(AttributeKey::PHP_DOC_INFO)
        );

        if ($this->isObjectType($propertyFetch->var, new ObjectType('TYPO3\CMS\Frontend\Page\PageRepository'))) {
            return false;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch->var,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnParentVariableOfTypePageRepository($propertyFetch);
    }
}
