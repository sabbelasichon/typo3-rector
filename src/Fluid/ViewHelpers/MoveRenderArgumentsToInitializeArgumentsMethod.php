<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Fluid\ViewHelpers;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use PHPStan\Type\VerbosityLevel;
use Rector\BetterPhpDocParser\Attributes\Ast\PhpDoc\AttributeAwareParamTagValueNode;
use Rector\Exception\NotImplementedException;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\PerNodeTypeResolver\ParamTypeResolver;
use Rector\NodeTypeResolver\StaticTypeMapper;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MoveRenderArgumentsToInitializeArgumentsMethod extends AbstractRector
{
    private const INITIALIZE_ARGUMENTS_METHOD_NAME = 'initializeArguments';

    /**
     * @var ParamTypeResolver
     */
    private $paramTypeResolver;
    /**
     * @var ParamTypeInferer
     */
    private $paramTypeInferer;

    /**
     * MoveRenderArgumentsToInitializeArgumentsMethod constructor.
     *
     * @param ParamTypeResolver $paramTypeResolver
     */
    public function __construct(ParamTypeResolver $paramTypeResolver, ParamTypeInferer $paramTypeInferer, StaticTypeMapper $staticTypeMapper)
    {
        $this->paramTypeResolver = $paramTypeResolver;
        $this->paramTypeInferer = $paramTypeInferer;
        $this->staticTypeMapper = $staticTypeMapper;
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Node|Class_ $node
     *
     * @return Node|null
     * @throws ShouldNotHappenException
     * @throws NotImplementedException
     *
     */
    public function refactor(Node $node): ?Node
    {
        if ( ! $node instanceof Class_) {
            return null;
        }

        if ($node->isAbstract()) {
            return null;
        }

        $nodeParentClassName = $this->getName($node->extends);
        if (AbstractViewHelper::class !== $nodeParentClassName) {
            return null;
        }

        // Check if the ViewHelper has a render method with params
        $classMethods = $node->getMethods();
        $renderMethod = null;

        foreach ($classMethods as $classMethod) {
            if ('render' === $this->getName($classMethod->name) && ! empty($classMethod->getParams())) {
                $renderMethod = $classMethod;
                break;
            }
        }

        if (null === $renderMethod) {
            return null;
        }

        // Remove all parameters from render method
        $stmts = [];
        $registerArgumentStmts = [];
        $paramTags = $this->getParamTags($renderMethod);

        $this->manipulateDocBlocks($renderMethod);

        [$stmts, $registerArgumentStmts] = $this->createRegisterArgumentsCalls($renderMethod, $paramTags, $stmts, $registerArgumentStmts);

        $initializeArgumentsMethodNode = $this->findNodeForInitializeArgumentsMethod($node);
        $initializeArgumentsMethodNode->stmts = array_merge($initializeArgumentsMethodNode->stmts, $registerArgumentStmts);

        $renderMethod->stmts = array_merge($stmts, $renderMethod->stmts);

        return $node;
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove empty method calls not required by parents', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class MyViewHelper implements ViewHelperInterface 
{
    public function render(array $firstParameter, string $secondParameter = null)
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class MyViewHelper implements ViewHelperInterface 
{
    public function initializeArguments() 
    {
        $this->registerArgument('firstParameter', 'array', '', true);
        $this->registerArgument('secondParameter', 'string', '', false, null);
    }
    
    public function render()
    {
        $firstParameter = $this->arguments['firstParameter'];
        $secondParameter = $this->arguments['secondParameter'];
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param Class_ $node
     *
     * @return ClassMethod
     */
    private function findNodeForInitializeArgumentsMethod(Class_ $node): ClassMethod
    {
        $initializeArgumentsMethodNode = $node->getMethod(self::INITIALIZE_ARGUMENTS_METHOD_NAME);

        if (null !== $initializeArgumentsMethodNode) {
            return $initializeArgumentsMethodNode;
        }

        // @TODO: Add call to parent initializeArguments method
        $initializeArgumentsMethodNode = $this->createInitializeArgumentsClassMethod();
        $node->stmts[] = new Nop();
        $node->stmts[] = $initializeArgumentsMethodNode;

        return $initializeArgumentsMethodNode;
    }

    /**
     * @return ClassMethod
     */
    private function createInitializeArgumentsClassMethod(): ClassMethod
    {
        $methodBuilder = $this->builderFactory->method(self::INITIALIZE_ARGUMENTS_METHOD_NAME);
        $methodBuilder->makePublic();
        $methodBuilder->setReturnType('void');

        return $methodBuilder->getNode();
    }

    /**
     * @param ClassMethod $node
     *
     * @return array
     * @throws ShouldNotHappenException
     *
     */
    private function getParamTags(ClassMethod $node): ?array
    {
        $phpDocInfo = $this->docBlockManipulator->createPhpDocInfoFromNode($node);
        $paramTags = [];
        $types = $phpDocInfo->getParamTagValues();
        if ($types === []) {
            return [];
        }
        foreach ($types as $i => $paramTagValueNode) {
            $paramTags[ltrim($paramTagValueNode->parameterName, '$')] = $paramTagValueNode;
        }

        return $paramTags;
    }

    /**
     * @param Node $node
     */
    private function manipulateDocBlocks(Node $node): void
    {
        $returnTags = $this->docBlockManipulator->getTagsByName($node, 'return');
        $this->docBlockManipulator->removeTagFromNode($node, 'param', true);
        $this->docBlockManipulator->removeTagFromNode($node, 'return', true);

        foreach ($returnTags as $returnTag) {
            $this->docBlockManipulator->addTag($node, $returnTag);
        }
    }

    /**
     * @param ClassMethod $renderMethod
     * @param array|null $paramTags
     * @param array $stmts
     * @param array $registerArgumentStmts
     *
     * @return array
     * @throws NotImplementedException
     */
    private function createRegisterArgumentsCalls(ClassMethod $renderMethod, ?array $paramTags, array $stmts, array $registerArgumentStmts): array
    {
        foreach ($renderMethod->params as $paramNode) {
            $description = '';

            $paramTag = array_key_exists($paramNode->var->name, $paramTags) ? $paramTags[$paramNode->var->name] : '';
            if ($paramTag instanceof AttributeAwareParamTagValueNode) {
                $description = $paramTag->description;
            }

            $stmts[] = new Assign(new Variable($paramNode->var->name), new Variable(sprintf("this->arguments['%s'];", $paramNode->var->name)));
            $this->removeNode($paramNode);

            $paramType = $this->getParamType($paramNode);

            if ($paramNode->default instanceof Expr) {
                $registerArgumentStmts[] = new Variable(sprintf("this->registerArgument('%s', '%s', '%s', %s, '%s');", $paramNode->var->name, $paramType, $description, $this->createFalse()->name, $paramNode->default->value));
            } else {
                $registerArgumentStmts[] = new Variable(sprintf("this->registerArgument('%s', '%s', '%s', %s);", $paramNode->var->name, $paramType, $description, $this->createTrue()->name));
            }
        }

        return [$stmts, $registerArgumentStmts];
    }

    /**
     * @param Param $paramNode
     *
     * @return Type|string
     * @throws NotImplementedException
     */
    private function getParamType(Param $paramNode)
    {
        return $this->staticTypeMapper->mapPHPStanTypeToDocString($this->paramTypeResolver->resolve($paramNode));
    }
}
