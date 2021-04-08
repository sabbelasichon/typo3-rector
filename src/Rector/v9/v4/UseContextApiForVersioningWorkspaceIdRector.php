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

        if (! $this->isName($node->name, 'versioningWorkspaceId')) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        // Check if we have an assigment to the property, if so do not change it
        if ($parentNode instanceof Assign && $parentNode->var instanceof PropertyFetch) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                $this->nodeFactory->createClassConstReference(Context::class),
            ]),
            'getPropertyFromAspect',
            ['workspace', 'id', 0]
        );
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

    private function shouldSkip(PropertyFetch $node): bool
    {
        $node->var->setAttribute(AttributeKey::PHP_DOC_INFO, $node->getAttribute(AttributeKey::PHP_DOC_INFO));

        if ($this->isObjectType($node->var, new ObjectType(PageRepository::class))) {
            return false;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node->var,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->typo3NodeResolver->isPropertyFetchOnParentVariableOfTypePageRepository($node);
    }
}
