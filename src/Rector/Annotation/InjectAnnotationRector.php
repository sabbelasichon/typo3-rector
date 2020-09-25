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
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Renaming\ValueObject\RenameAnnotation;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Feature-82869-ReplaceInjectWithTYPO3CMSExtbaseAnnotationInject.html
 */
final class InjectAnnotationRector extends AbstractRector
{
    /**
     * @var string
     */
    private const OLD_ANNOTATION = 'inject';

    /**
     * @var string
     */
    private const NEW_ANNOTATION = 'TYPO3\CMS\Extbase\Annotation\Inject';

    /**
     * @return string[]
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
        $injectMethods = [];

        $properties = $node->getProperties();
        foreach ($properties as $property) {
            /** @var PhpDocInfo|null $propertyPhpDocInfo */
            $propertyPhpDocInfo = $property->getAttribute(AttributeKey::PHP_DOC_INFO);
            if (null === $propertyPhpDocInfo) {
                return null;
            }

            if (! $propertyPhpDocInfo->hasByName(self::OLD_ANNOTATION)) {
                continue;
            }

            // If the property is public, then change the annotation name
            if ($property->isPublic()) {
                $this->docBlockManipulator->replaceAnnotationInNode(
                    $property,
                    new RenameAnnotation('', self::OLD_ANNOTATION, self::NEW_ANNOTATION)
                );
                continue;
            }

            // Remove the old annotation and use setterInjection instead
            $propertyPhpDocInfo->removeByName(self::OLD_ANNOTATION);

            /** @var string $variableName */
            $variableName = $this->getName($property);

            $paramBuilder = $this->builderFactory->param($variableName);
            $varType = $propertyPhpDocInfo->getVarType();

            if (! $varType instanceof ObjectType) {
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

    private function createInjectClassMethod(string $variableName, Param $param, Assign $assign): ClassMethod
    {
        $injectMethodName = 'inject' . ucfirst($variableName);

        $injectMethodBuilder = $this->builderFactory->method($injectMethodName);
        $injectMethodBuilder->makePublic();
        $injectMethodBuilder->addParam($param);
        $injectMethodBuilder->setReturnType('void');
        $injectMethodBuilder->addStmt($assign);

        return $injectMethodBuilder->getNode();
    }
}
