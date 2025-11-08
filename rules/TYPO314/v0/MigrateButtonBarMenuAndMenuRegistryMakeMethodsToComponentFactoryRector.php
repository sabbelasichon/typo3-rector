<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\NodeManipulator\ClassDependencyManipulator;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107824-ButtonBarMakeMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector\MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRectorTest
 */
final class MigrateButtonBarMenuAndMenuRegistryMakeMethodsToComponentFactoryRector extends AbstractRector implements DocumentedRuleInterface
{
    private const COMPONENT_FACTORY_CLASS = 'TYPO3\CMS\Backend\Template\Components\ComponentFactory';

    private const COMPONENT_FACTORY_PROPERTY = 'componentFactory';

    /**
     * @var array<string, array<string, string>>
     */
    private const CLASS_TO_METHOD_MAP = [
        'TYPO3\CMS\Backend\Template\Components\ButtonBar' => [
            'makeGenericButton' => 'createGenericButton',
            'makeInputButton' => 'createInputButton',
            'makeSplitButton' => 'createSplitButton',
            'makeDropDownButton' => 'createDropDownButton',
            'makeLinkButton' => 'createLinkButton',
            'makeFullyRenderedButton' => 'createFullyRenderedButton',
            'makeShortcutButton' => 'createShortcutButton',
        ],
        'TYPO3\CMS\Backend\Template\Components\Menu\Menu' => [
            'makeMenuItem' => 'createMenuItem',
        ],
        'TYPO3\CMS\Backend\Template\Components\MenuRegistry' => [
            'makeMenu' => 'createMenu',
        ],
    ];

    private const MAKE_BUTTON_METHOD = 'makeButton';

    private const BUTTON_BAR_CLASS = 'TYPO3\CMS\Backend\Template\Components\ButtonBar';

    /**
     * @readonly
     */
    private ClassDependencyManipulator $classDependencyManipulator;

    public function __construct(ClassDependencyManipulator $classDependencyManipulator)
    {
        $this->classDependencyManipulator = $classDependencyManipulator;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate ButtonBar, Menu, and MenuRegistry make* methods to ComponentFactory create* methods',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;

class MyController
{
    protected ModuleTemplate $moduleTemplate;

    public function myAction(): void
    {
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $linkButton = $buttonBar->makeLinkButton()->setTitle('My Link');
        $customButton = $buttonBar->makeButton(MyCustomButton::class);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\Components\ComponentFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyController
{
    protected ModuleTemplate $moduleTemplate;

    public function __construct(
        private readonly ComponentFactory $componentFactory
    ) {
    }

    public function myAction(): void
    {
        $buttonBar = $this->moduleTemplate->getDocHeaderComponent()->getButtonBar();
        $linkButton = $this->componentFactory->createLinkButton()->setTitle('My Link');
        $customButton = GeneralUtility::makeInstance(MyCustomButton::class);
    }
}
CODE_SAMPLE
                ),

            ]
        );
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
        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $stmt) use (&$hasChanged): ?Node {
            if (! $stmt instanceof MethodCall) {
                return null;
            }

            // ButtonBar::makeButton()
            if ($this->isName($stmt->name, self::MAKE_BUTTON_METHOD)
                && $this->isObjectType($stmt->var, new ObjectType(self::BUTTON_BAR_CLASS))
            ) {
                $firstArg = $stmt->args[0] ?? null;
                if (! $firstArg instanceof Arg) {
                    return null;
                }

                $hasChanged = true;
                return $this->nodeFactory->createStaticCall(
                    'TYPO3\CMS\Core\Utility\GeneralUtility',
                    'makeInstance',
                    [$firstArg]
                );
            }

            foreach (self::CLASS_TO_METHOD_MAP as $className => $methodMap) {
                if (! $this->isObjectType($stmt->var, new ObjectType($className))) {
                    continue;
                }

                foreach ($methodMap as $oldMethod => $newMethod) {
                    if ($this->isName($stmt->name, $oldMethod)) {
                        // Replace $var->make*() with $this->componentFactory->create*()
                        $stmt->var = $this->nodeFactory->createPropertyFetch('this', self::COMPONENT_FACTORY_PROPERTY);
                        $stmt->name = new Identifier($newMethod);
                        $hasChanged = true;
                        return $stmt;
                    }
                }
            }

            return null;
        });

        if ($hasChanged) {
            if (\PHP_VERSION_ID >= PhpVersionFeature::READONLY_PROPERTY) {
                $flags = Modifiers::PRIVATE & Modifiers::READONLY;
            } else {
                $flags = Modifiers::PRIVATE;
            }

            $propertyMetadata = new PropertyMetadata(
                self::COMPONENT_FACTORY_PROPERTY,
                new ObjectType(self::COMPONENT_FACTORY_CLASS),
                $flags
            );

            $this->classDependencyManipulator->addConstructorDependency($node, $propertyMetadata);
            return $node;
        }

        return null;
    }
}
