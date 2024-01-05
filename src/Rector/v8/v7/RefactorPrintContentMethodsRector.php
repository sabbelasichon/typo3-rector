<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.7/Deprecation-80445-DeprecatePrintContentMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorPrintContentMethodsRector\RefactorPrintContentMethodsRectorTest
 */
final class RefactorPrintContentMethodsRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($node->expr)) {
            return null;
        }

        if (! $this->isName($node->expr->name, 'printContent')) {
            return null;
        }

        if ($this->isPageLayoutControllerClass($node->expr)) {
            $echo = new Echo_([
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createMethodCall($node->expr->var, 'getModuleTemplate'),
                    'renderContent'
                ),
            ]);
        } else {
            $echo = new Echo_([$this->nodeFactory->createPropertyFetch($node->expr->var, 'content')]);
        }

        return $echo;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Refactor printContent methods of classes TaskModuleController and PageLayoutController',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Taskcenter\Controller\TaskModuleController;

$pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
$pageLayoutController->printContent();

$taskLayoutController = GeneralUtility::makeInstance(TaskModuleController::class);
$taskLayoutController->printContent();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Controller\PageLayoutController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Taskcenter\Controller\TaskModuleController;

$pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
echo $pageLayoutController->getModuleTemplate()->renderContent();

$taskLayoutController = GeneralUtility::makeInstance(TaskModuleController::class);
echo $taskLayoutController->content;
CODE_SAMPLE
                ),

            ]
        );
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if ($this->isPageLayoutControllerClass($methodCall)) {
            return false;
        }

        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Taskcenter\Controller\TaskModuleController')
        );
    }

    private function isPageLayoutControllerClass(MethodCall $methodCall): bool
    {
        return $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Backend\Controller\PageLayoutController')
        );
    }
}
