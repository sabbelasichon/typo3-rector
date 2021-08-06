<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Cast\Bool_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85004-DeprecateMethodsInReflectionService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector\UseClassSchemaInsteadReflectionServiceMethodsRectorTest
 */
final class UseClassSchemaInsteadReflectionServiceMethodsRector extends AbstractRector
{
    /**
     * @var string
     */
    private const HAS_METHOD = 'hasMethod';

    /**
     * @var string
     */
    private const TAGS = 'tags';

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Instead of fetching reflection data via ReflectionService use ClassSchema directly',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
class MyService
{
    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $properties = $this->reflectionService->getClassPropertyNames(\stdClass::class);
    }
}
CODE_SAMPLE
                    ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Reflection\ReflectionService;
class MyService
{
    /**
     * @var ReflectionService
     * @inject
     */
    protected $reflectionService;

    public function init(): void
    {
        $properties = array_keys($this->reflectionService->getClassSchema(stdClass::class)->getProperties());
    }
}
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Reflection\ReflectionService')
        )) {
            return null;
        }

        if (! $this->isNames($node->name, [
            'getClassPropertyNames',
            'getPropertyTagsValues',
            'getPropertyTagValues',
            'getClassTagsValues',
            'getClassTagValues',
            'getMethodTagsValues',
            self::HAS_METHOD,
            'getMethodParameters',
            'isClassTaggedWith',
            'isPropertyTaggedWith',
        ])) {
            return null;
        }

        if ([] === $node->args) {
            return null;
        }

        $nodeName = $this->getName($node->name);

        if (null === $nodeName) {
            return null;
        }

        if ('getClassPropertyNames' === $nodeName) {
            return $this->refactorGetClassPropertyNamesMethod($node);
        }

        if ('getPropertyTagsValues' === $nodeName) {
            return $this->refactorGetPropertyTagsValuesMethod($node);
        }

        if ('getPropertyTagValues' === $nodeName) {
            return $this->refactorGetPropertyTagValuesMethod($node);
        }

        if ('getClassTagsValues' === $nodeName) {
            return $this->refactorGetClassTagsValues($node);
        }

        if ('getClassTagValues' === $nodeName) {
            return $this->refactorGetClassTagValues($node);
        }

        if ('getMethodTagsValues' === $nodeName) {
            return $this->refactorGetMethodTagsValues($node);
        }

        if (self::HAS_METHOD === $nodeName) {
            return $this->refactorHasMethod($node);
        }

        if ('getMethodParameters' === $nodeName) {
            return $this->refactorGetMethodParameters($node);
        }

        if ('isClassTaggedWith' === $nodeName) {
            return $this->refactorIsClassTaggedWith($node);
        }

        return $this->refactorIsPropertyTaggedWith($node);
    }

    private function refactorGetPropertyTagsValuesMethod(MethodCall $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        $propertyTagsValuesVariable = new Variable('propertyTagsValues');
        $propertyTagsValuesNode = new Expression(new Assign($propertyTagsValuesVariable, new Coalesce(
            $this->createArrayDimFetchTags($node),
            $this->nodeFactory->createArray([])
        )));

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmt ?? $node;
        $this->addNodeBeforeNode($propertyTagsValuesNode, $positionNode);

        return $propertyTagsValuesVariable;
    }

    private function refactorGetClassPropertyNamesMethod(MethodCall $node): Node
    {
        return $this->nodeFactory->createFuncCall(
            'array_keys',
            [$this->nodeFactory->createMethodCall($this->createClassSchema($node), 'getProperties')]
        );
    }

    private function refactorGetPropertyTagValuesMethod(MethodCall $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        if (! isset($node->args[2])) {
            return null;
        }

        return new Coalesce(
            new ArrayDimFetch($this->createArrayDimFetchTags($node), $node->args[2]->value),
            $this->nodeFactory->createArray([])
        );
    }

    private function createArrayDimFetchTags(MethodCall $node): ArrayDimFetch
    {
        return new ArrayDimFetch(
            $this->nodeFactory->createMethodCall(
                $this->createClassSchema($node),
                'getProperty',
                [$node->args[1]->value]
            ),
            new String_(self::TAGS)
        );
    }

    private function refactorGetClassTagsValues(MethodCall $node): MethodCall
    {
        return $this->nodeFactory->createMethodCall($this->createClassSchema($node), 'getTags');
    }

    private function refactorGetClassTagValues(MethodCall $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        return new Coalesce(
            new ArrayDimFetch($this->refactorGetClassTagsValues($node), $node->args[1]->value),
            $this->nodeFactory->createArray([])
        );
    }

    private function refactorGetMethodTagsValues(MethodCall $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        return new Coalesce(new ArrayDimFetch(
            $this->nodeFactory->createMethodCall($this->createClassSchema($node), 'getMethod', [$node->args[1]->value]),
            new String_(self::TAGS)
        ), $this->nodeFactory->createArray([]));
    }

    private function refactorHasMethod(MethodCall $node): ?Node
    {
        if (! isset($node->args[1])) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->createClassSchema($node),
            self::HAS_METHOD,
            [$node->args[1]->value]
        );
    }

    private function refactorGetMethodParameters(MethodCall $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::NULL_COALESCE)) {
            return null;
        }

        if (! isset($node->args[1])) {
            return null;
        }

        return new Coalesce(new ArrayDimFetch(
            $this->nodeFactory->createMethodCall($this->createClassSchema($node), 'getMethod', [$node->args[1]->value]),
            new String_('params')
        ), $this->nodeFactory->createArray([]));
    }

    private function refactorIsPropertyTaggedWith(MethodCall $node): ?Node
    {
        if (! isset($node->args[1], $node->args[2])) {
            return null;
        }

        $propertyVariable = new Variable('propertyReflectionService');
        $propertyNode = new Expression(new Assign($propertyVariable, $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall($node->var, 'getClassSchema', [$node->args[0]->value]),
            'getProperty',
            [$node->args[1]->value]
        )));

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmt ?? $node;
        $this->addNodeBeforeNode($propertyNode, $positionNode);

        return new Ternary(
            new Empty_($propertyVariable),
            $this->nodeFactory->createFalse(),
            new Isset_(
                [
                    new ArrayDimFetch(new ArrayDimFetch($propertyVariable, new String_(
                        self::TAGS
                    )), $node->args[2]->value),
                ]
            )
        );
    }

    private function refactorIsClassTaggedWith(MethodCall $node): ?Node
    {
        if (! isset($node->args[1])) {
            return null;
        }

        $tagValue = $node->args[1]->value;
        $closureUse = $tagValue instanceof Variable ? $tagValue : new Variable('tag');
        if (! $tagValue instanceof Variable) {
            $tempVarNode = new Expression(new Assign($closureUse, $tagValue));
            $this->addNodeBeforeNode($tempVarNode, $node->getAttribute(AttributeKey::PARENT_NODE));
        }

        $anonymousFunction = new Closure();
        $anonymousFunction->uses[] = new ClosureUse($closureUse);
        $anonymousFunction->params = [new Param(new Variable('tagName'))];
        $anonymousFunction->stmts[] = new Return_(new Identical(new Variable('tagName'), $closureUse));

        return new Bool_($this->nodeFactory->createFuncCall('count', [
            $this->nodeFactory->createFuncCall('array_filter', [
                $this->nodeFactory->createFuncCall('array_keys', [$this->refactorGetClassTagsValues($node)]),
                $anonymousFunction,
            ]),
        ]));
    }

    private function createClassSchema(MethodCall $node): MethodCall
    {
        return $this->nodeFactory->createMethodCall($node->var, 'getClassSchema', [$node->args[0]->value]);
    }
}
