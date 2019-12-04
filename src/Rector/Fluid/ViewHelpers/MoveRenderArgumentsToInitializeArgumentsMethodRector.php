<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\Attributes\Ast\PhpDoc\AttributeAwareParamTagValueNode;
use Rector\BetterPhpDocParser\Attributes\Ast\PhpDoc\Type\AttributeAwareUnionTypeNode;
use Rector\Exception\NotImplementedException;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PerNodeTypeResolver\ParamTypeResolver;
use Rector\NodeTypeResolver\StaticTypeMapper;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class MoveRenderArgumentsToInitializeArgumentsMethodRector extends AbstractRector
{
    /**
     * @var string
     */
    private const INITIALIZE_ARGUMENTS_METHOD_NAME = 'initializeArguments';

    /**
     * @var ParamTypeResolver
     */
    private $paramTypeResolver;

    /**
     * MoveRenderArgumentsToInitializeArgumentsMethod constructor.
     *
     * @param ParamTypeResolver $paramTypeResolver
     * @param StaticTypeMapper $staticTypeMapper
     */
    public function __construct(ParamTypeResolver $paramTypeResolver, StaticTypeMapper $staticTypeMapper)
    {
        $this->paramTypeResolver = $paramTypeResolver;
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
     * @throws NotImplementedException
     * @throws ShouldNotHappenException
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isAbstract()) {
            return null;
        }

        if (!$this->isObjectType($node, AbstractViewHelper::class) && !$this->isObjectType($node, \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper::class)) {
            return null;
        }

        // Check if the ViewHelper has a render method with params, if not return immediately
        $classMethods = $node->getMethods();
        $renderMethod = null;

        foreach ($classMethods as $classMethod) {
            if ('render' === $this->getName($classMethod->name) && !empty($classMethod->getParams())) {
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

        // Manipulate the doc blocks
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

        $initializeArgumentsMethodNode = $this->createInitializeArgumentsClassMethod();

        // Add call to parent initializeArguments method
        $parentClassName = $node->getAttribute(AttributeKey::PARENT_CLASS_NAME);
        // not in analyzed scope, nothing we can do
        if ((null !== $parentClassName) && method_exists($parentClassName, self::INITIALIZE_ARGUMENTS_METHOD_NAME)) {
            $parentConstructCallNode = new StaticCall(
                new Name('parent'),
                new Identifier(self::INITIALIZE_ARGUMENTS_METHOD_NAME)
            );
            $initializeArgumentsMethodNode->stmts[] = new Expression($parentConstructCallNode);
        }

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
     * @throws ShouldNotHappenException
     *
     * @return ParamTagValueNode[]
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
        $this->docBlockManipulator->removeTagFromNode($node, 'param', true);
    }

    /**
     * @param ClassMethod $renderMethod
     * @param array|null $paramTags
     * @param array $stmts
     * @param array $registerArgumentStmts
     *
     * @throws NotImplementedException
     *
     * @return array
     */
    private function createRegisterArgumentsCalls(ClassMethod $renderMethod, ?array $paramTags, array $stmts, array $registerArgumentStmts): array
    {
        foreach ($renderMethod->params as $param) {
            $paramTag = $paramTags[$param->var->name] ?? null;
            $description = $paramTag instanceof AttributeAwareParamTagValueNode ? $paramTag->description : '';

            // Add assignments in render function
            $stmts[] = new Expression(new Assign(new Variable($param->var->name), $this->createPropertyFetch('this', sprintf('arguments[\'%s\']', $param->var->name))));
            $this->removeNode($param);

            $paramType = $this->inferParamType($param, $paramTag);

            if ($param->default instanceof Expr) {
                $registerArgumentStmts[] = new Expression($this->nodeFactory->createMethodCall('this', 'registerArgument', [$param->var->name, $paramType, $description, $this->createFalse(), $param->default->value]));
            } else {
                $registerArgumentStmts[] = new Expression($this->nodeFactory->createMethodCall('this', 'registerArgument', [$param->var->name, $paramType, $description, $this->createTrue()]));
            }
        }

        return [$stmts, $registerArgumentStmts];
    }

    /**
     * @param Param $param
     * @param ParamTagValueNode $paramTag
     *
     * @throws NotImplementedException
     *
     * @return string
     */
    private function inferParamType(Param $param, ParamTagValueNode $paramTag): string
    {
        if (null === $param->type) {
            if ($paramTag->type instanceof AttributeAwareUnionTypeNode) {
                return 'mixed';
            }

            return (string) $paramTag->type;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToDocString($this->paramTypeResolver->resolve($param));
    }
}
