<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\NodeFactory;

use PhpParser\Builder\Method;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
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
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\VerbosityLevel;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\PhpParser\Node\NodeFactory;
use Rector\Core\PhpParser\Node\Value\ValueResolver;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Ssch\TYPO3Rector\TypeInferer\ParamTypeInferer\FunctionLikeDocParamTypeInferer;

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
     * @readonly
     */
    private NodeFactory $nodeFactory;

    /**
     * @readonly
     */
    private NodeNameResolver $nodeNameResolver;

    /**
     * @readonly
     */
    private StaticTypeMapper $staticTypeMapper;

    /**
     * @readonly
     */
    private FunctionLikeDocParamTypeInferer $paramTypeInferer;

    /**
     * @readonly
     */
    private PhpDocInfoFactory $phpDocInfoFactory;

    /**
     * @readonly
     */
    private ReflectionProvider $reflectionProvider;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    /**
     * @readonly
     */
    private AstResolver $astResolver;

    public function __construct(
        NodeFactory $nodeFactory,
        NodeNameResolver $nodeNameResolver,
        StaticTypeMapper $staticTypeMapper,
        FunctionLikeDocParamTypeInferer $paramTypeInferer,
        PhpDocInfoFactory $phpDocInfoFactory,
        ReflectionProvider $reflectionProvider,
        ValueResolver $valueResolver,
        AstResolver $astResolver
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->nodeNameResolver = $nodeNameResolver;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->paramTypeInferer = $paramTypeInferer;
        $this->phpDocInfoFactory = $phpDocInfoFactory;
        $this->reflectionProvider = $reflectionProvider;
        $this->valueResolver = $valueResolver;
        $this->astResolver = $astResolver;
    }

    public function decorateClass(Class_ $class): void
    {
        $renderClassMethod = $class->getMethod('render');
        if (! $renderClassMethod instanceof ClassMethod) {
            return;
        }

        $newStmts = $this->createStmts($renderClassMethod, $class);

        $classMethod = $this->findOrCreateInitializeArgumentsClassMethod($class);
        $classMethod->stmts = $this->mergeRegisterArgumentStatements($classMethod, $newStmts);
    }

    private function findOrCreateInitializeArgumentsClassMethod(Class_ $class): ClassMethod
    {
        $classMethod = $class->getMethod(self::METHOD_NAME);
        if ($classMethod instanceof ClassMethod) {
            return $classMethod;
        }

        $classMethod = $this->createNewClassMethod();

        if ($this->doesParentClassMethodExist($class, self::METHOD_NAME)) {
            // not in analyzed scope, nothing we can do
            $parentConstructStaticCall = new StaticCall(new Name('parent'), new Identifier(self::METHOD_NAME));
            $classMethod->stmts[] = new Expression($parentConstructStaticCall);
        }

        // empty line between methods
        $class->stmts[] = new Nop();

        $class->stmts[] = $classMethod;

        return $classMethod;
    }

    private function createNewClassMethod(): ClassMethod
    {
        $methodBuilder = new Method(self::METHOD_NAME);
        $methodBuilder->makePublic();
        $methodBuilder->setReturnType('void');

        return $methodBuilder->getNode();
    }

    /**
     * @return Expression[]
     */
    private function createStmts(ClassMethod $renderMethod, Class_ $class): array
    {
        $argumentsAlreadyDefinedInParentCall = $this->extractArgumentsFromParentClasses($class);

        $paramTagsByName = $this->getParamTagsByName($renderMethod);

        $stmts = [];

        foreach ($renderMethod->params as $param) {
            $paramName = $this->nodeNameResolver->getName($param->var);

            if (in_array($paramName, $argumentsAlreadyDefinedInParentCall, true)) {
                continue;
            }

            $paramTagValueNode = $paramTagsByName[$paramName] ?? null;

            $docString = $this->createTypeInString($paramTagValueNode, $param);

            $docString = $this->transformDocStringToClassConstantIfPossible($docString);

            $args = [$paramName, $docString, $this->getDescription($paramTagValueNode)];

            if ($param->default instanceof Expr) {
                $args[] = new ConstFetch(new Name('false'));
                $defaultValue = $this->valueResolver->getValue($param->default);
                if ($defaultValue !== null && $defaultValue !== 'null') {
                    $args[] = $defaultValue;
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
                $paramTagValueNode = $phpDocTagNode->value;

                if (! $paramTagValueNode instanceof ParamTagValueNode) {
                    continue;
                }

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
        if ($param->type !== null) {
            return $this->resolveParamType($param->type);
        }

        if ($paramTagValueNode instanceof ParamTagValueNode && $paramTagValueNode->type instanceof IdentifierTypeNode) {
            return $paramTagValueNode->type->name;
        }

        $inferredType = $this->paramTypeInferer->inferParam($param);

        if ($inferredType instanceof MixedType) {
            return self::MIXED;
        }

        if ($this->isTraitType($inferredType)) {
            return self::MIXED;
        }

        $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($inferredType, TypeKind::PARAM);
        if ($paramTypeNode instanceof UnionType) {
            return self::MIXED;
        }

        if ($paramTypeNode instanceof NullableType) {
            return self::MIXED;
        }

        if ($paramTypeNode instanceof Name) {
            return $paramTypeNode->__toString();
        }

        if (! $paramTagValueNode instanceof ParamTagValueNode) {
            return self::MIXED;
        }

        $phpStanType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $paramTagValueNode->type,
            $param
        );

        $docString = $phpStanType->describe(VerbosityLevel::typeOnly());
        if (str_ends_with($docString, '[]')) {
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
        if (! $this->reflectionProvider->hasClass($fullyQualifiedName)) {
            return false;
        }

        $reflectionClass = $this->reflectionProvider->getClass($fullyQualifiedName);

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

    /**
     * @return MethodReflection[]
     */
    private function getParentClassesMethodReflection(Class_ $class, string $methodName): array
    {
        $scope = $class->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        $parentMethods = [];

        foreach ($classReflection->getParents() as $parentClassReflection) {
            if ($parentClassReflection->hasMethod($methodName)) {
                $parentMethods[] = $parentClassReflection->getMethod($methodName, $scope);
            }
        }

        return $parentMethods;
    }

    private function doesParentClassMethodExist(Class_ $class, string $methodName): bool
    {
        return $this->getParentClassesMethodReflection($class, $methodName) !== [];
    }

    /**
     * @return array<int, string>
     */
    private function extractArgumentsFromParentClasses(Class_ $class): array
    {
        $definedArguments = [];
        $methodReflections = $this->getParentClassesMethodReflection($class, self::METHOD_NAME);

        foreach ($methodReflections as $methodReflection) {
            $classMethod = $this->astResolver->resolveClassMethodFromMethodReflection($methodReflection);
            if (! $classMethod instanceof ClassMethod) {
                continue;
            }

            if ($classMethod->stmts === null) {
                continue;
            }

            foreach ($classMethod->stmts as $stmt) {
                if (! $stmt instanceof Expression) {
                    continue;
                }

                if (! $stmt->expr instanceof MethodCall) {
                    continue;
                }

                if (! $this->nodeNameResolver->isName($stmt->expr->name, 'registerArgument')) {
                    continue;
                }

                $value = $this->valueResolver->getValue($stmt->expr->args[0]->value);

                if ($value === null) {
                    continue;
                }

                $definedArguments[] = $value;
            }
        }

        return $definedArguments;
    }

    /**
     * @return ClassConstFetch|string
     */
    private function transformDocStringToClassConstantIfPossible(string $docString)
    {
        // remove leading slash
        $classLikeName = ltrim($docString, '\\');
        if ($classLikeName === '') {
            return $docString;
        }

        if (! $this->doesClassLikeExist($classLikeName)) {
            return $classLikeName;
        }

        $fullyQualified = new FullyQualified($classLikeName);

        return new ClassConstFetch($fullyQualified, 'class');
    }

    private function doesClassLikeExist(string $classLike): bool
    {
        if (class_exists($classLike)) {
            return true;
        }

        if (interface_exists($classLike)) {
            return true;
        }

        return trait_exists($classLike);
    }

    /**
     * @param Expression[] $newRegisterArgumentStatements
     *
     * @return Node\Stmt[]
     */
    private function mergeRegisterArgumentStatements(
        ClassMethod $initializeArgumentsClassMethod,
        array $newRegisterArgumentStatements
    ): array {
        $alreadyExistingArguments = [];
        foreach ((array) $initializeArgumentsClassMethod->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof MethodCall) {
                continue;
            }

            if (! $this->nodeNameResolver->isName($stmt->expr->name, 'registerArgument')) {
                continue;
            }

            $alreadyExistingArguments[] = $this->valueResolver->getValue($stmt->expr->args[0]->value);
        }

        $keepNewRegisterArgumentStatements = array_filter(
            $newRegisterArgumentStatements,
            function (Expression $expression) use ($alreadyExistingArguments) {
                if (! $expression->expr instanceof MethodCall) {
                    return true;
                }

                if (! $this->nodeNameResolver->isName($expression->expr->name, 'registerArgument')) {
                    return true;
                }

                $newRegisterArgumentName = $this->valueResolver->getValue($expression->expr->args[0]->value);

                return ! in_array($newRegisterArgumentName, $alreadyExistingArguments, true);
            }
        );

        return array_merge((array) $initializeArgumentsClassMethod->stmts, $keepNewRegisterArgumentStatements);
    }
}
