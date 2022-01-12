<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v11\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Core\NodeManipulator\ClassDependencyManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToReplaceCollector;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/11.5/Deprecation-95235-PublicGetterOfServicesInModuleTemplate.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRectorTest
 */
final class SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector extends AbstractRector
{
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
        $iconFactoryMethodCalls = $this->findModuleTemplateMethodCallsByName($node, 'getIconFactory');
        $pageRendererMethodCalls = $this->findModuleTemplateMethodCallsByName($node, 'getPageRenderer');

        if ([] === $iconFactoryMethodCalls && [] === $pageRendererMethodCalls) {
            return null;
        }

        if ([] !== $iconFactoryMethodCalls) {
            $this->addIconFactoryToConstructor($node);

            foreach ($iconFactoryMethodCalls as $iconFactoryMethodCall) {
                $this->nodesToReplaceCollector->addReplaceNodeWithAnotherNode(
                    $iconFactoryMethodCall,
                    $this->nodeFactory->createPropertyFetch('this', 'iconFactory')
                );
            }
        }

        if ([] !== $pageRendererMethodCalls) {
            $this->addPageRendererToConstructor($node);

            foreach ($pageRendererMethodCalls as $pageRendererMethodCall) {
                $this->nodesToReplaceCollector->addReplaceNodeWithAnotherNode(
                    $pageRendererMethodCall,
                    $this->nodeFactory->createPropertyFetch('this', 'pageRenderer')
                );
            }
        }

        // change the node
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use PageRenderer and IconFactory directly instead of getting them from the ModuleTemplate',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
    {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
    }

    public function myAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->getPageRenderer()->loadRequireJsModule('Vendor/Extension/MyJsModule');
        $moduleTemplate->setContent($moduleTemplate->getIconFactory()->getIcon('some-icon', Icon::SIZE_SMALL)->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
CODE_SAMPLE
                ,
                    <<<'CODE_SAMPLE'
class MyController extends ActionController
{
    protected ModuleTemplateFactory $moduleTemplateFactory;
    protected IconFactory $iconFactory;
    protected PageRenderer $pageRenderer;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        IconFactory $iconFactory,
        PageRenderer $pageRenderer
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->iconFactory = $iconFactory;
        $this->pageRenderer = $pageRenderer;
    }

    public function myAction(): ResponseInterface
    {
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $this->pageRenderer->loadRequireJsModule('Vendor/Extension/MyJsModule');
        $moduleTemplate->setContent($this->iconFactory->getIcon('some-icon', Icon::SIZE_SMALL)->render());
        return $this->htmlResponse($moduleTemplate->renderContent());
    }
}
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return Node[]
     */
    private function findModuleTemplateMethodCallsByName(Class_ $node, string $methodCallName): array
    {
        return $this->betterNodeFinder->find($node->stmts, function (Node $node) use ($methodCallName) {
            if (! $node instanceof MethodCall) {
                return false;
            }

            if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $node,
                new ObjectType('TYPO3\CMS\Backend\Template\ModuleTemplate')
            )) {
                return false;
            }

            return $this->nodeNameResolver->isName($node->name, $methodCallName);
        });
    }

    private function addIconFactoryToConstructor(Class_ $node): void
    {
        $this->classDependencyManipulator->addConstructorDependency(
            $node,
            new PropertyMetadata(
                'iconFactory',
                new ObjectType('TYPO3\CMS\Core\Imaging\IconFactory'),
                Class_::MODIFIER_PRIVATE
            )
        );
    }

    private function addPageRendererToConstructor(Class_ $node): void
    {
        $this->classDependencyManipulator->addConstructorDependency(
            $node,
            new PropertyMetadata(
                'pageRenderer',
                new ObjectType('TYPO3\CMS\Core\Page\PageRenderer'),
                Class_::MODIFIER_PRIVATE
            )
        );
    }
}
