<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102621-MostTSFEMembersMarkedInternalOrRead-only.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerSysPageRector\MigrateTypoScriptFrontendControllerSysPageRectorTest
 */
final class MigrateTypoScriptFrontendControllerSysPageRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `TypoScriptFrontendController->sys_page`', [new CodeSample(
            <<<'CODE_SAMPLE'
$sys_page = $GLOBALS['TSFE']->sys_page;
$GLOBALS['TSFE']->sys_page->enableFields('table');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;

$sys_page = GeneralUtility::makeInstance(PageRepository::class);
GeneralUtility::makeInstance(PageRepository::class)->enableFields('table');
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class, MethodCall::class];
    }

    /**
     * @param Assign|MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $expressionToAnalyze = $this->getExpressionToAnalyze($node);
        if (! $expressionToAnalyze instanceof Expr) {
            return null;
        }

        if ($this->isTsfeSysPage($expressionToAnalyze)) {
            $this->replaceExpression($node, $this->createMakeInstanceCall());
            return $node;
        }

        return null;
    }

    /**
     * Based on the node type, returns the relevant sub-expression to check.
     */
    private function getExpressionToAnalyze(Node $node): ?Expr
    {
        if ($node instanceof Assign) {
            return $node->expr;
        }

        if ($node instanceof MethodCall) {
            return $node->var;
        }

        return null;
    }

    /**
     * Checks if the given expression is a property fetch for $GLOBALS['TSFE']->sys_page
     */
    private function isTsfeSysPage(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch) {
            return false;
        }

        return ! $this->shouldSkip($expr);
    }

    private function shouldSkip(PropertyFetch $propertyFetch): bool
    {
        if (! $this->isGlobals($propertyFetch)
            && ! $this->isObjectType(
                $propertyFetch->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            )
        ) {
            return true;
        }

        return ! $this->isName($propertyFetch->name, 'sys_page');
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }

    /**
     * Replaces the relevant sub-expression on the node.
     *
     * @param Assign|MethodCall $node
     */
    private function replaceExpression(Node $node, Expr $replacement): void
    {
        if ($node instanceof Assign) {
            $node->expr = $replacement;
            return;
        }

        if ($node instanceof MethodCall) {
            $node->var = $replacement;
        }
    }

    /**
     * Creates the new GeneralUtility::makeInstance(PageRepository::class) node.
     */
    private function createMakeInstanceCall(): StaticCall
    {
        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\GeneralUtility',
            'makeInstance',
            [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Domain\Repository\PageRepository')]
        );
    }
}
