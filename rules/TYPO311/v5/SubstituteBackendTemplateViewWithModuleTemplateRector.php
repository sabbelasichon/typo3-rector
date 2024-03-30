<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v5;

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
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeAnalyzer\ExtbaseControllerRedirectAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.5/Deprecation-95164-ExtbackendBackendTemplateView.html
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

    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    /**
     * @readonly
     */
    private ExtbaseControllerRedirectAnalyzer $extbaseControllerRedirectAnalyzer;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private BetterNodeFinder $betterNodeFinder;

    public function __construct(
        ClassDependencyManipulator $classDependencyManipulator,
        ExtbaseControllerRedirectAnalyzer $extbaseControllerRedirectAnalyzer,
        ValueResolver $valueResolver,
        BetterNodeFinder $betterNodeFinder
    ) {
        $this->classDependencyManipulator = $classDependencyManipulator;
        $this->extbaseControllerRedirectAnalyzer = $extbaseControllerRedirectAnalyzer;
        $this->valueResolver = $valueResolver;
        $this->betterNodeFinder = $betterNodeFinder;
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
        $this->removePropertyFromClass($node, 'defaultViewObjectName');
        $this->removePropertyFromClass($node, 'view');

        $classMethods = $node->getMethods();

        foreach ($classMethods as $classMethod) {
            if ($this->extbaseControllerRedirectAnalyzer->hasRedirectCall(
                $classMethod,
                ['redirect', 'redirectToUri']
            )) {
                continue;
            }

            $this->substituteModuleTemplateMethodCalls($classMethod);
            $this->callSetContentAndGetContent($classMethod);
        }

        return $node;
    }

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

    private function shouldSkip(Class_ $class): bool
    {
        $defaultViewObjectNameProperty = $class->getProperty('defaultViewObjectName');
        if (! $defaultViewObjectNameProperty instanceof Property) {
            return true;
        }

        $defaultViewObjectName = $defaultViewObjectNameProperty->props[0]->default;

        if (! $defaultViewObjectName instanceof Expr) {
            return true;
        }

        return ! $this->valueResolver->isValue($defaultViewObjectName, 'TYPO3\CMS\Backend\View\BackendTemplateView');
    }

    private function addModuleTemplateFactoryToConstructor(Class_ $class): void
    {
        $this->classDependencyManipulator->addConstructorDependency(
            $class,
            new PropertyMetadata(
                self::MODULE_TEMPLATE_FACTORY,
                new ObjectType('TYPO3\CMS\Backend\Template\ModuleTemplateFactory'),
                Class_::MODIFIER_PRIVATE
            )
        );
    }

    private function removePropertyFromClass(Class_ $class, string $propertyName): void
    {
        foreach ($class->stmts as $stmtKey => $stmt) {
            if ($stmt instanceof Property) {
                foreach ($stmt->props as $prop) {
                    if ($prop instanceof PropertyProperty && $propertyName === $prop->name->toString()) {
                        unset($class->stmts[$stmtKey]);
                    }
                }
            }
        }
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
        if ($classMethod->stmts === null) {
            return;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($classMethod->stmts, function (Node $node) use (&$hasChanged) {
            if (! $node instanceof MethodCall) {
                return null;
            }

            if (! $this->isName($node->name, 'getModuleTemplate')) {
                return null;
            }

            $hasChanged = true;

            return new Variable(self::MODULE_TEMPLATE);
        });

        if (! $hasChanged) {
            return;
        }

        $this->callModuleTemplateFactoryCreateIfNeeded($classMethod);
    }

    private function callSetContentAndGetContent(ClassMethod $classMethod): void
    {
        $classMethodName = (string) $this->getName($classMethod->name);

        if (! str_ends_with($classMethodName, 'Action')
            || (str_starts_with($classMethodName, 'initialize') && str_ends_with($classMethodName, 'Action'))
        ) {
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

        $htmlResponseMethodCallReturn = new Return_($htmlResponseMethodCall);

        if ($classMethod->stmts === null) {
            $classMethod->stmts[] = $this->createModuleTemplateAssignment();
            $classMethod->stmts[] = $callSetContentOnModuleTemplateVariable;
            $classMethod->stmts[] = $htmlResponseMethodCallReturn;
            return;
        }

        $this->callModuleTemplateFactoryCreateIfNeeded($classMethod);

        $existingHtmlResponseMethodCallNodes = $this->findAllExistingHtmlResponseMethodCalls($classMethod->stmts ?? []);

        if ($existingHtmlResponseMethodCallNodes === []) {
            $classMethod->stmts[] = $callSetContentOnModuleTemplateVariable;
            $classMethod->stmts[] = $htmlResponseMethodCallReturn;
            return;
        }

        $classMethodStatements = [];
        foreach ($classMethod->stmts ?? [] as $classMethodStatement) {
            $existingHtmlResponseMethodCallNodes = $this->findAllExistingHtmlResponseMethodCalls(
                [$classMethodStatement]
            );

            if ($existingHtmlResponseMethodCallNodes === []) {
                $classMethodStatements[] = $classMethodStatement;
                continue;
            }

            foreach ($existingHtmlResponseMethodCallNodes as $existingHtmlResponseMethodCallNode) {
                if (! $existingHtmlResponseMethodCallNode instanceof MethodCall) {
                    continue;
                }

                $classMethodStatements[] = $callSetContentOnModuleTemplateVariable;
                $classMethodStatements[] = $classMethodStatement;
                $existingHtmlResponseMethodCallNode->args = $this->nodeFactory->createArgs([
                    $moduleTemplateRenderContentMethodCall,
                ]);
            }
        }

        $classMethod->stmts = $classMethodStatements;
    }

    private function callModuleTemplateFactoryCreateIfNeeded(ClassMethod $classMethod): void
    {
        if ($classMethod->stmts === null) {
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

        if ($existingModuleTemplateFactoryCreateMethodCall === []) {
            $moduleTemplateFactoryAssignment = $this->createModuleTemplateAssignment();
            array_unshift($classMethod->stmts, $moduleTemplateFactoryAssignment);
        }
    }

    /**
     * @param Node[] $nodes
     * @return Node[]
     */
    private function findAllExistingHtmlResponseMethodCalls(array $nodes): array
    {
        return $this->betterNodeFinder->find(
            $nodes,
            function (Node $node) {
                if (! $node instanceof MethodCall) {
                    return false;
                }

                if (! $this->isName($node->name, 'htmlResponse')) {
                    return false;
                }

                return $node->args === [];
            }
        );
    }
}
