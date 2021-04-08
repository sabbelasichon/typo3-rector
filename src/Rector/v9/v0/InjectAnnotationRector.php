<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

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
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockTagReplacer;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder;
use Symplify\Astral\ValueObject\NodeBuilder\ParamBuilder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

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
     * @var PhpDocTagRemover
     */
    private $phpDocTagRemover;

    /**
     * @var DocBlockTagReplacer
     */
    private $docBlockTagReplacer;

    public function __construct(PhpDocTagRemover $phpDocTagRemover, DocBlockTagReplacer $docBlockTagReplacer)
    {
        $this->phpDocTagRemover = $phpDocTagRemover;
        $this->docBlockTagReplacer = $docBlockTagReplacer;
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
        $injectMethods = [];
        $properties = $node->getProperties();
        foreach ($properties as $property) {
            /** @var PhpDocInfo|null $propertyPhpDocInfo */
            $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            if (null === $propertyPhpDocInfo) {
                continue;
            }
            if (! $propertyPhpDocInfo->hasByName(self::OLD_ANNOTATION)) {
                continue;
            }
            // If the property is public, then change the annotation name
            if ($property->isPublic()) {
                $this->docBlockTagReplacer->replaceTagByAnother(
                    $propertyPhpDocInfo,
                    self::OLD_ANNOTATION,
                    self::NEW_ANNOTATION
                );
                continue;
            }

            /** @var string $variableName */
            $variableName = $this->getName($property);

            $paramBuilder = new ParamBuilder($variableName);
            $varType = $propertyPhpDocInfo->getVarType();
            if (! $varType instanceof ObjectType) {
                continue;
            }

            // Remove the old annotation and use setterInjection instead
            $this->phpDocTagRemover->removeByName($propertyPhpDocInfo, self::OLD_ANNOTATION);

            if ($varType instanceof FullyQualifiedObjectType) {
                $paramBuilder->setType(new FullyQualified($varType->getClassName()));
            } elseif ($varType instanceof ShortenedObjectType) {
                $paramBuilder->setType($varType->getShortName());
            }

            $param = $paramBuilder->getNode();
            $propertyFetch = new PropertyFetch(new Variable('this'), $variableName);
            $assign = new Assign($propertyFetch, new Variable($variableName));
            // Add new line and then the method
            $injectMethods[] = new Nop();

            $methodAlreadyExists = $node->getMethod($this->createInjectMethodName($variableName));

            if (null === $methodAlreadyExists) {
                $injectMethods[] = $this->createInjectClassMethod($variableName, $param, $assign);
            }
        }
        $node->stmts = array_merge($node->stmts, $injectMethods);
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns properties with `@inject` to setter injection', [
            new CodeSample(<<<'CODE_SAMPLE'
/**
 * @var SomeService
 * @inject
 */
private $someService;
CODE_SAMPLE
, <<<'CODE_SAMPLE'
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
        ]);
    }

    private function createInjectClassMethod(string $variableName, Param $param, Assign $assign): ClassMethod
    {
        $injectMethodName = $this->createInjectMethodName($variableName);

        $injectMethodBuilder = new MethodBuilder($injectMethodName);
        $injectMethodBuilder->makePublic();
        $injectMethodBuilder->addParam($param);
        $injectMethodBuilder->setReturnType('void');
        $injectMethodBuilder->addStmt($assign);

        return $injectMethodBuilder->getNode();
    }

    private function createInjectMethodName(string $variableName): string
    {
        return 'inject' . ucfirst($variableName);
    }
}
