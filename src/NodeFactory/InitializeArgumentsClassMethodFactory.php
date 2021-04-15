<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\VerbosityLevel;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Rector\TypeDeclaration\TypeInferer\ParamTypeInferer;
use ReflectionClass;
use Symplify\Astral\ValueObject\NodeBuilder\MethodBuilder;

final class InitializeArgumentsClassMethodFactory
{
    /**
     * @var string
     */
    private const METHOD_NAME = 'initializeArguments';

    /**
     * @var string
     */
    private const MIXED = 'mixed';

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;

    /**
     * @var ParamTypeInferer
     */
    private $paramTypeInferer;

    /**
     * @var PhpDocInfoFactory
     */
    private $phpDocInfoFactory;

    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;

    public function __construct(
        NodeFactory $nodeFactory,
        NodeNameResolver $nodeNameResolver,
        StaticTypeMapper $staticTypeMapper,
        ParamTypeInferer $paramTypeInferer,
        PhpDocInfoFactory $phpDocInfoFactory
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->paramTypeInferer = $paramTypeInferer;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
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

    private function findOrCreateInitializeArgumentsClassMethod(Class_ $class): ClassMethod
    {
        $classMethod = $class->getMethod(self::METHOD_NAME);
        if (null !== $classMethod) {
            return $classMethod;
        }

        $classMethod = $this->createNewClassMethod();

        if ($this->doesParentClassMethodExist($class, self::METHOD_NAME)) {
            // not in analyzed scope, nothing we can do
            $parentConstructCallNode = new StaticCall(new Name('parent'), new Identifier(self::METHOD_NAME));
            $classMethod->stmts[] = new Expression($parentConstructCallNode);
        }

        // empty line between methods
        $class->stmts[] = new Nop();

        $class->stmts[] = $classMethod;

        return $classMethod;
    }

    private function createNewClassMethod(): ClassMethod
    {
        $methodBuilder = new MethodBuilder(self::METHOD_NAME);
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

            $args = [$paramName, $docString, $this->getDescription($paramTagValueNode)];

            if ($param->default instanceof Expr) {
                $args[] = new ConstFetch(new Name('false'));
                if (property_exists($param->default, 'value')) {
                    $args[] = $param->default->value;
                }
            } else {
                $args[] = new ConstFetch(new Name('true'));
            }

            $methodCall = $this->nodeFactory->createMethodCall('this', 'registerArgument', $args);
            $stmts[] = new Expression($methodCall);
        }

        return $stmts;
    }

    /**
     * @return array<string, ParamTagValueNode>
     */
    private function getParamTagsByName(ClassMethod $classMethod): array
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($classMethod);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return [];
        }

        $paramTagsByName = [];
        foreach ($phpDocInfo->getTagsByName('param') as $phpDocTagNode) {
            if (property_exists($phpDocTagNode, 'value')) {
                /** @var ParamTagValueNode $paramTagValueNode */
                $paramTagValueNode = $phpDocTagNode->value;
                $paramName = ltrim($paramTagValueNode->parameterName, '$');
                $paramTagsByName[$paramName] = $paramTagValueNode;
            }
        }

        return $paramTagsByName;
    }

    private function getDescription(?ParamTagValueNode $paramTagValueNode): string
    {
        return $paramTagValueNode instanceof ParamTagValueNode ? $paramTagValueNode->description : '';
    }

    private function createTypeInString(?ParamTagValueNode $paramTagValueNode, Param $param): string
    {
        if (null !== $param->type) {
            return $this->resolveParamType($param->type);
        }

        if (null !== $paramTagValueNode && $paramTagValueNode->type instanceof IdentifierTypeNode) {
            return $paramTagValueNode->type->name;
        }

        $inferedType = $this->paramTypeInferer->inferParam($param);
        if ($inferedType instanceof MixedType) {
            return self::MIXED;
        }

        if ($this->isTraitType($inferedType)) {
            return self::MIXED;
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($inferedType, TypeKind::KIND_PARAM);
        if ($paramTypeNode instanceof UnionType) {
            return self::MIXED;
        }

        if ($paramTypeNode instanceof NullableType) {
            return self::MIXED;
        }

        if (null !== $paramTypeNode) {
            return $paramTypeNode->toString();
        }

        if (null === $paramTagValueNode) {
            return self::MIXED;
        }

        $phpStanType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
    $paramTagValueNode->type,
    $param
);

        $docString = $phpStanType->describe(VerbosityLevel::typeOnly());
        if ('[]' === substr($docString, -2)) {
            return 'array';
        }

        return $docString;
    }

    private function isTraitType(Type $type): bool
    {
        if (! $type instanceof TypeWithClassName) {
            return false;
        }

        $fullyQualifiedName = $this->getFullyQualifiedName($type);

        if (! class_exists($fullyQualifiedName)) {
            return false;
        }

        $reflectionClass = new ReflectionClass($fullyQualifiedName);

        return $reflectionClass->isTrait();
    }

    private function getFullyQualifiedName(TypeWithClassName $typeWithClassName): string
    {
        if ($typeWithClassName instanceof ShortenedObjectType) {
            return $typeWithClassName->getFullyQualifiedName();
        }

        return $typeWithClassName->getClassName();
    }

    private function resolveParamType(Node $paramType): string
    {
        if ($paramType instanceof FullyQualified) {
            return $paramType->toCodeString();
        }

        return $this->nodeNameResolver->getName($paramType) ?? self::MIXED;
    }

    private function doesParentClassMethodExist(Class_ $class, string $methodName): bool
    {
        $scope = $class->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $classReflection = $scope->getClassReflection();
        if (null === $classReflection) {
            return false;
        }

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasMethod($methodName)) {
                return true;
            }
        }

        return false;
    }
}
