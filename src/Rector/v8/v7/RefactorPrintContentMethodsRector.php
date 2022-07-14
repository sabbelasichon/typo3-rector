<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Echo_;
use PHPStan\Type\ObjectType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80445-DeprecatePrintContentMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\RefactorPrintContentMethodsRector\RefactorPrintContentMethodsRectorTest
 */
final class RefactorPrintContentMethodsRector extends AbstractRector
{
    /**
     * @readonly
     */
    public NodesToAddCollector $nodesToAddCollector;

    public function __construct(NodesToAddCollector $nodesToAddCollector)
    {
        $this->nodesToAddCollector = $nodesToAddCollector;
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

        if (! $this->isName($node->name, 'printContent')) {
            return null;
        }

        if ($this->isPageLayoutControllerClass($node)) {
            $echo = new Echo_([
                $this->nodeFactory->createMethodCall(
                    $this->nodeFactory->createMethodCall($node->var, 'getModuleTemplate'),
                    'renderContent'
                ),
            ]);
        } else {
            $echo = new Echo_([$this->nodeFactory->createPropertyFetch($node->var, 'content')]);
        }

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            $this->removeNode($parentNode);
        }

        $this->nodesToAddCollector->addNodeBeforeNode($echo, $node);

        return $node;
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
