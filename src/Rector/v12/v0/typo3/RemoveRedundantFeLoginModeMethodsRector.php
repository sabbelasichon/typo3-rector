<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96616-RemoveFrontendLoginModeForPages.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRedundantFeLoginModeMethodsRector\RemoveRedundantFeLoginModeMethodsRectorTest
 */
final class RemoveRedundantFeLoginModeMethodsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private RectorOutputStyle $rectorOutputStyle;

    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    public function __construct(RectorOutputStyle $rectorOutputStyle, Typo3NodeResolver $typo3NodeResolver)
    {
        $this->rectorOutputStyle = $rectorOutputStyle;
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;

        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        if ($this->isName($methodCall->name, 'checkIfLoginAllowedInBranch')) {
            $this->rectorOutputStyle->note(
                'Please remove the usage of TypoScriptFrontendController->checkIfLoginAllowedInBranch(). Also check the changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96616-RemoveFrontendLoginModeForPages.html for further migration advice.'
            );
            return null;
        }

        if (! $this->isName($methodCall->name, 'hideActiveLogin')) {
            return null;
        }

        $this->rectorOutputStyle->note(
            'Please check the changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96616-RemoveFrontendLoginModeForPages.html for further migration advice.'
        );

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove redundant methods that are used to handle fe_login_mode', [new CodeSample(
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication->hideActiveLogin();
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
-
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication')
        )) {
            return false;
        }

        if ($this->typo3NodeResolver->isAnyMethodCallOnGlobals(
            $methodCall,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return false;
        }

        return ! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }
}
