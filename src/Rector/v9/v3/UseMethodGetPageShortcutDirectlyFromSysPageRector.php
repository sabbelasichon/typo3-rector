<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-85130-TSFE-getPageShortcutMovedToPageRepository.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v3\UseMethodGetPageShortcutDirectlyFromSysPageRector\UseMethodGetPageShortcutDirectlyFromSysPageRectorTest
 */
final class UseMethodGetPageShortcutDirectlyFromSysPageRector extends AbstractRector
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

        if (! $this->isName($node->name, 'getPageShortcut')) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            new PropertyFetch($node->var, new Identifier('sys_page')),
            'getPageShortcut',
            $node->args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use method getPageShortcut directly from PageRepository', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->getPageShortcut('shortcut', 1, 1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1);
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
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
