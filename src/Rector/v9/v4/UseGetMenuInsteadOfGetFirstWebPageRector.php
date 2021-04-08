<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85971-DeprecatePageRepository-getFirstWebPage.html
 */
final class UseGetMenuInsteadOfGetFirstWebPageRector extends AbstractRector
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
     * @return array<class-string<\PhpParser\Node>>
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

        if (! $this->isName($node->name, 'getFirstWebPage')) {
            return null;
        }

        $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);

        if (! $parentNode instanceof Assign) {
            return null;
        }

        $rootLevelPagesVariable = new Variable('rootLevelPages');
        $this->addRootLevelPagesAssignment($rootLevelPagesVariable, $node);

        $resetRootLevelPagesNode = $this->nodeFactory->createFuncCall('reset', [$rootLevelPagesVariable]);

        $ifNode = new If_(new BooleanNot(new Empty_($rootLevelPagesVariable)));
        $parentNode->expr = $resetRootLevelPagesNode;
        $ifNode->stmts[] = new Expression($parentNode);

        $this->addNodeBeforeNode($ifNode, $node);

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            $this->removeNode($parentNode);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use method getMenu instead of getFirstWebPage', [
            new CodeSample(<<<'CODE_SAMPLE'
$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
$rootLevelPages = $GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false);
if (!empty($rootLevelPages)) {
    $theFirstPage = reset($rootLevelPages);
}
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, PageRepository::class)) {
            return false;
        }

        return ! $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'sys_page'
        );
    }

    private function addRootLevelPagesAssignment(Variable $rootLevelPagesVariable, MethodCall $node): void
    {
        $rootLevelPagesNode = new Assign($rootLevelPagesVariable, $this->nodeFactory->createMethodCall(
            $node->var,
            'getMenu',
            [$node->args[0], 'uid', 'sorting', '', false]
        ));
        $this->addNodeBeforeNode($rootLevelPagesNode, $node);
    }
}
