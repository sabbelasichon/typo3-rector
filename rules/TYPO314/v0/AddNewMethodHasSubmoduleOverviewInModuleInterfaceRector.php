<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107712-NewMethodHasSubmoduleOverviewInModuleInterface.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector\AddNewMethodHasSubmoduleOverviewInModuleInterfaceRectorTest
 */
final class AddNewMethodHasSubmoduleOverviewInModuleInterfaceRector extends AbstractRector implements DocumentedRuleInterface
{
    private BuilderFactory $builderFactory;

    public function __construct(BuilderFactory $builderFactory)
    {
        $this->builderFactory = $builderFactory;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add new method `hasSubmoduleOverview()` in ModuleInterface', [new CodeSample(
            <<<'CODE_SAMPLE'
class MyBackendModule implements \TYPO3\CMS\Backend\Module\ModuleInterface
{
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
class MyBackendModule implements \TYPO3\CMS\Backend\Module\ModuleInterface
{
    public function hasSubmoduleOverview(): bool
    {
        return $this->configuration['showSubmoduleOverview'] ?? false;
    }
}
CODE_SAMPLE
        )]);
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
        if (! $this->isObjectType($node, new ObjectType('TYPO3\CMS\Backend\Module\ModuleInterface'))) {
            return null;
        }

        if ($node->getMethod('hasSubmoduleOverview') instanceof ClassMethod) {
            return null;
        }

        $newMethod = $this->buildNewMethod();

        $node->stmts[] = $newMethod;

        return $node;
    }

    private function buildNewMethod(): ClassMethod
    {
        // Build: $this->configuration
        $propertyFetch = $this->builderFactory->propertyFetch($this->builderFactory->var('this'), 'configuration');

        // Build: $this->configuration['showSubmoduleOverview']
        $arrayDimFetch = new ArrayDimFetch($propertyFetch, $this->builderFactory->val('showSubmoduleOverview'));

        // Build: ... ?? false
        $coalesce = new Coalesce($arrayDimFetch, $this->builderFactory->val(false));

        // Build: return ...;
        $returnStmt = new Return_($coalesce);

        // Build the method
        $methodBuilder = $this->builderFactory
            ->method('hasSubmoduleOverview')
            ->makePublic()
            ->setReturnType(new Identifier('bool'))
            ->addStmt($returnStmt);

        return $methodBuilder->getNode();
    }
}
