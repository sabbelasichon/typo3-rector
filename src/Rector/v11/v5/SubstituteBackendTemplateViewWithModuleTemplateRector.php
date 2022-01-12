<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeManipulator\ClassDependencyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToReplaceCollector;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.5/Deprecation-95164-ExtbackendBackendTemplateView.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector\SubstituteBackendTemplateViewWithModuleTemplateRectorTest
 */
final class SubstituteBackendTemplateViewWithModuleTemplateRector extends AbstractRector
{
    /**
     * @var string
     */
    private const MODULE_TEMPLATE_FACTORY = 'moduleTemplateFactory';

    /**
     * @var string
     */
    private const THIS = 'this';

    /**
     * @var string
     */
    private const MODULE_TEMPLATE = 'moduleTemplate';

    public function __construct(
        private ClassDependencyManipulator $classDependencyManipulator,
        private NodesToReplaceCollector $nodesToReplaceCollector
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $this->addModuleTemplateFactoryToConstructor($node);
        $this->removePropertyDefaultViewObjectName($node);
        $this->removePropertyViewIfNeeded($node);

        $classMethods = $node->getMethods();

        foreach ($classMethods as $classMethod) {
            $this->substituteModuleTemplateMethodCalls($classMethod);
            $this->callSetContentAndGetContent($classMethod);
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use an instance of ModuleTemplate instead of BackendTemplateView', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyController extends ActionController
{
    protected $defaultViewObjectName = BackendTemplateView::class;

    public function myAction(): ResponseInterface
    {
        $this->view->assign('someVar', 'someContent');
        $moduleTemplate = $this->view->getModuleTemplate();
        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        return $this->htmlResponse();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function myAction(): ResponseInterface
    {
        $this->view->assign('someVar', 'someContent');
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        // Adding title, menus, buttons, etc. using $moduleTemplate ...
        $moduleTemplate->setContent($this->view->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(Class_ $node): bool
    {
        $defaultViewObjectNameProperty = $node->getProperty('defaultViewObjectName');
        if (! $defaultViewObjectNameProperty instanceof Property) {
            return true;
        }

        $defaultViewObjectName = $defaultViewObjectNameProperty->props[0]->default;

        if (! $defaultViewObjectName instanceof Expr) {
            return true;
        }

        return ! $this->valueResolver->isValue($defaultViewObjectName, 'TYPO3\CMS\Backend\View\BackendTemplateView');
    }

    private function addModuleTemplateFactoryToConstructor(Class_ $node): void
    {
        $this->classDependencyManipulator->addConstructorDependency(
            $node,
            new PropertyMetadata(
                self::MODULE_TEMPLATE_FACTORY,
                new ObjectType('TYPO3\CMS\Backend\Template\ModuleTemplateFactory'),
                Class_::MODIFIER_PRIVATE
            )
        );
    }

    private function removePropertyDefaultViewObjectName(Class_ $node): void
    {
        $defaultViewObjectNameProperty = $node->getProperty('defaultViewObjectName');
        if (! $defaultViewObjectNameProperty instanceof Property) {
            return;
        }

        $this->nodeRemover->removeNode($defaultViewObjectNameProperty);
    }

    private function removePropertyViewIfNeeded(Class_ $node): void
    {
        $viewProperty = $node->getProperty('view');
        if (! $viewProperty instanceof Property) {
            return;
        }

        $this->nodeRemover->removeNode($viewProperty);
    }

    private function createModuleTemplateAssignment(): Expression
    {
        $moduleTemplateFactoryCall = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createPropertyFetch(self::THIS, self::MODULE_TEMPLATE_FACTORY),
            'create',
            [$this->nodeFactory->createPropertyFetch(self::THIS, 'request')]
        );

        return new Expression(new Assign(new Variable(self::MODULE_TEMPLATE), $moduleTemplateFactoryCall));
    }

    private function substituteModuleTemplateMethodCalls(ClassMethod $classMethod): void
    {
        if (null === $classMethod->stmts) {
            return;
        }

        /** @var MethodCall[] $moduleTemplateMethodCalls */
        $moduleTemplateMethodCalls = $this->betterNodeFinder->find($classMethod->stmts, function (Node $node) {
            if (! $node instanceof MethodCall) {
                return false;
            }

            return $this->isName($node->name, 'getModuleTemplate');
        });

        if ([] === $moduleTemplateMethodCalls) {
            return;
        }

        $this->callModuleTemplateFactoryCreateIfNeeded($classMethod);

        foreach ($moduleTemplateMethodCalls as $moduleTemplateMethodCall) {
            $this->nodesToReplaceCollector->addReplaceNodeWithAnotherNode(
                $moduleTemplateMethodCall,
                new Variable(self::MODULE_TEMPLATE)
            );
        }
    }

    private function callSetContentAndGetContent(ClassMethod $classMethod): void
    {
        $classMethodName = (string) $this->getName($classMethod->name);

        if (! str_ends_with($classMethodName, 'Action')) {
            return;
        }

        $classMethod->returnType = new FullyQualified('Psr\Http\Message\ResponseInterface');

        $viewPropertyFetch = $this->nodeFactory->createPropertyFetch(self::THIS, 'view');
        $viewRenderMethodCall = $this->nodeFactory->createMethodCall($viewPropertyFetch, 'render');
        $callSetContentOnModuleTemplateVariable = new Expression($this->nodeFactory->createMethodCall(
            self::MODULE_TEMPLATE,
            'setContent',
            [$viewRenderMethodCall]
        ));

        $moduleTemplateRenderContentMethodCall = $this->nodeFactory->createMethodCall(
            self::MODULE_TEMPLATE,
            'renderContent'
        );

        $htmlResponseMethodCall = $this->nodeFactory->createMethodCall(self::THIS, 'htmlResponse', [
            $moduleTemplateRenderContentMethodCall,
        ]);

        $returnHtmlResponseMethodCall = new Return_($htmlResponseMethodCall);

        if (null === $classMethod->stmts) {
            $classMethod->stmts[] = $this->createModuleTemplateAssignment();
            $classMethod->stmts[] = $callSetContentOnModuleTemplateVariable;
            $classMethod->stmts[] = $returnHtmlResponseMethodCall;
            return;
        }

        $this->callModuleTemplateFactoryCreateIfNeeded($classMethod);

        /** @var MethodCall[] $existingHtmlResponseMethodCallNodes */
        $existingHtmlResponseMethodCallNodes = $this->betterNodeFinder->find(
            (array) $classMethod->stmts,
            function (Node $node) {
                if (! $node instanceof MethodCall) {
                    return false;
                }

                if (! $this->isName($node->name, 'htmlResponse')) {
                    return false;
                }

                return [] === $node->args;
            }
        );

        if ([] === $existingHtmlResponseMethodCallNodes) {
            $classMethod->stmts[] = $callSetContentOnModuleTemplateVariable;
            $classMethod->stmts[] = $returnHtmlResponseMethodCall;
            return;
        }

        foreach ($existingHtmlResponseMethodCallNodes as $existingHtmlResponseMethodCallNode) {
            $this->nodesToAddCollector->addNodeBeforeNode(
                $callSetContentOnModuleTemplateVariable,
                $existingHtmlResponseMethodCallNode
            );
            $existingHtmlResponseMethodCallNode->args = $this->nodeFactory->createArgs([
                $moduleTemplateRenderContentMethodCall,
            ]);
        }
    }

    private function callModuleTemplateFactoryCreateIfNeeded(ClassMethod $classMethod): void
    {
        if (null === $classMethod->stmts) {
            $classMethod->stmts[] = $this->createModuleTemplateAssignment();
            return;
        }

        $existingModuleTemplateFactoryCreateMethodCall = $this->betterNodeFinder->find(
            (array) $classMethod->stmts,
            function (Node $node) {
                if (! $node instanceof MethodCall) {
                    return false;
                }

                if (! $node->var instanceof PropertyFetch) {
                    return false;
                }

                if (! $this->isName($node->var->name, self::MODULE_TEMPLATE_FACTORY)) {
                    return false;
                }

                return $this->isName($node->name, 'create');
            }
        );

        if ([] === $existingModuleTemplateFactoryCreateMethodCall) {
            $moduleTemplateFactoryAssignment = $this->createModuleTemplateAssignment();
            array_unshift($classMethod->stmts, $moduleTemplateFactoryAssignment);
        }
    }
}
