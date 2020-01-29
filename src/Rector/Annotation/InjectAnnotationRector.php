<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Annotation;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class InjectAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private $oldAnnotation = 'inject';

    /**
     * @var string
     */
    private $newAnnotation = 'TYPO3\CMS\Extbase\Annotation\Inject';

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Node|Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $injectMethods = [];

        $properties = $node->getProperties();
        foreach ($properties as $property) {
            if (!$this->docBlockManipulator->hasTag($property, $this->oldAnnotation)) {
                continue;
            }

            // If the property is public, then change the annotation name
            if ($property->isPublic()) {
                $this->docBlockManipulator->replaceAnnotationInNode($property, $this->oldAnnotation, $this->newAnnotation);
                continue;
            }

            // Remove the old annotation and use setterInjection instead
            $this->docBlockManipulator->removeTagFromNode($property, $this->oldAnnotation);

            $variableName = $this->getName($property);

            $paramBuilder = $this->builderFactory->param($variableName);
            $varType = $this->docBlockManipulator->getVarType($property);

            if (!$varType instanceof ObjectType) {
                continue;
            }

            $paramBuilder->setType(new FullyQualified($varType->getClassName()));
            $param = $paramBuilder->getNode();

            $propertyFetch = new PropertyFetch(new Variable('this'), $variableName);
            $assign = new Assign($propertyFetch, new Variable($variableName));

            // Add new line and then the method
            $injectMethods[] = new Nop();
            $injectMethods[] = $this->createInjectClassMethod($variableName, $param, $assign);
        }

        $node->stmts = array_merge($node->stmts, $injectMethods);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns properties with `@inject` to setter injection',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @var SomeService
 * @inject
 */
private $someService;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * @var SomeService
 */
private $someService;

public function injectSomeService(SomeService $someService)
{
    $this->someService = $someService;
}

CODE_SAMPLE
                ),
            ]
        );
    }

    private function createInjectClassMethod(
        string $variableName,
        Param $param,
        Assign $assign
    ): ClassMethod {
        $injectMethodName = 'inject' . ucfirst($variableName);

        $injectMethodBuilder = $this->builderFactory->method($injectMethodName);
        $injectMethodBuilder->makePublic();
        $injectMethodBuilder->addParam($param);
        $injectMethodBuilder->setReturnType('void');
        $injectMethodBuilder->addStmt($assign);

        return $injectMethodBuilder->getNode();
    }
}
