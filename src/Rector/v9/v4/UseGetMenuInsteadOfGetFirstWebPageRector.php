<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85971-DeprecatePageRepository-getFirstWebPage.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseGetMenuInsteadOfGetFirstWebPageRector\UseGetMenuInsteadOfGetFirstWebPageRectorTest
 */
final class UseGetMenuInsteadOfGetFirstWebPageRector extends AbstractRector
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

        $node->name = new Node\Identifier('getMenu');
        $node->args = $this->nodeFactory->createArgs([$node->args[0], 'uid', 'sorting', '', false]);

        return $this->nodeFactory->createFuncCall('reset', [$node]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use method getMenu instead of getFirstWebPage', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$theFirstPage = reset($GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false));
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Frontend\Page\PageRepository')
        ) && $this->typo3NodeResolver->isMethodCallOnPropertyOfGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER,
            'sys_page'
        )) {
            return true;
        }

        return ! $this->isName($methodCall->name, 'getFirstWebPage');
    }
}
