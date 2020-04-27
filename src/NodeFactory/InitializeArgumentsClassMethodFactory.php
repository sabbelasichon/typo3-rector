<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\BuilderFactory;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class InitializeArgumentsClassMethodFactory
{
    /**
     * @var string
     */
    private const METHOD_NAME = 'initializeArguments';

    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;

    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;

    public function __construct(
        BuilderFactory $builderFactory,
        NodeFactory $nodeFactory,
        NodeNameResolver $nodeNameResolver,
        StaticTypeMapper $staticTypeMapper
    ) {
        $this->builderFactory = $builderFactory;
        $this->nodeFactory = $nodeFactory;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->staticTypeMapper = $staticTypeMapper;
    }

    public function decorateClass(Class_ $class): void
    {
        $renderClassMethod = $class->getMethod('render');
        if (null === $renderClassMethod) {
            return;
        }

        $newStmts = $this->createStmts($renderClassMethod);

        $classMethod = $this->findOrCreateInitializeArgumentsClassMethod($class);
        $classMethod->stmts = array_merge((array) $classMethod->stmts, $newStmts);
    }

    public function findOrCreateInitializeArgumentsClassMethod(Class_ $class): ClassMethod
    {
        $classMethod = $class->getMethod(self::METHOD_NAME);
        if (null !== $classMethod) {
            return $classMethod;
        }

        $classMethod = $this->createNewClassMethod();

        $parentClassName = $class->getAttribute(AttributeKey::PARENT_CLASS_NAME);

        // not in analyzed scope, nothing we can do
        if ((null !== $parentClassName) && method_exists($parentClassName, self::METHOD_NAME)) {
            $parentConstructCallNode = new StaticCall(
                new Name('parent'),
                new Identifier(self::METHOD_NAME)
            );

            $classMethod->stmts[] = new Expression($parentConstructCallNode);
        }

        // empty line between methods
        $class->stmts[] = new Nop();

        $class->stmts[] = $classMethod;

        return $classMethod;
    }

    private function createNewClassMethod(): ClassMethod
    {
        $methodBuilder = $this->builderFactory->method(self::METHOD_NAME);
        $methodBuilder->makePublic();
        $methodBuilder->setReturnType('void');

        return $methodBuilder->getNode();
    }

    private function createStmts(ClassMethod $renderMethod): array
    {
        $paramTagsByName = $this->getParamTagsByName($renderMethod);

        $stmts = [];

        foreach ($renderMethod->params as $param) {
            $paramName = $this->nodeNameResolver->getName($param->var);
            $paramTagValueNode = $paramTagsByName[$paramName] ?? null;

            $docString = $this->createTypeInString($paramTagValueNode, $param);

            $args = [
                $paramName,
                $docString,
                $this->getDescription($paramTagValueNode),
            ];

            if ($param->default instanceof Expr) {
                $args[] = new ConstFetch(new Name('false'));
                $args[] = $param->default->value;
            } else {
                $args[] = new ConstFetch(new Name('true'));
            }

            $methodCall = $this->nodeFactory->createMethodCall('this', 'registerArgument', $args);
            $stmts[] = new Expression($methodCall);
        }

        return $stmts;
    }

    /**
     * @return ParamTagValueNode[]
     */
    private function getParamTagsByName(ClassMethod $classMethod): array
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $classMethod->getAttribute(AttributeKey::PHP_DOC_INFO);
        if (null === $phpDocInfo) {
            return [];
        }

        $paramTagsByName = [];
        foreach ($phpDocInfo->getTagsByName('param') as $phpDocTagNode) {
            /** @var ParamTagValueNode $paramTagValueNode */
            $paramTagValueNode = $phpDocTagNode->value;

            $paramName = ltrim($paramTagValueNode->parameterName, '$');
            $paramTagsByName[$paramName] = $paramTagValueNode;
        }

        return $paramTagsByName;
    }

    private function getDescription(?ParamTagValueNode $paramTagValueNode): string
    {
        return $paramTagValueNode instanceof AttributeAwareParamTagValueNode ? $paramTagValueNode->description : '';
    }

    private function createTypeInString(?ParamTagValueNode $paramTagValueNode, Param $param): string
    {
        if (null === $paramTagValueNode) {
            return 'mixed';
        }

        $docString = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPhpDocString($paramTagValueNode->type, $param);
        if ('[]' === substr($docString, -2)) {
            return 'array';
        }

        return $docString;
    }
}
