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
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85004-DeprecateMethodsInReflectionService.html
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
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Instead of fetching reflection data via ReflectionService use ClassSchema directly',
            [new CodeSample(<<<'PHP'
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
PHP
                    , <<<'PHP'
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
PHP
                )]);
    }

    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ReflectionService::class)) {
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

        if (0 === count($node->args)) {
            return null;
        }

        switch ($this->getName($node->name)) {
            case 'getClassPropertyNames':
                return $this->refactorGetClassPropertyNamesMethod($node);
            case 'getPropertyTagsValues':
                return $this->refactorGetPropertyTagsValuesMethod($node);
            case 'getPropertyTagValues':
                return $this->refactorGetPropertyTagValuesMethod($node);
            case 'getClassTagsValues':
                return $this->refactorGetClassTagsValues($node);
            case 'getClassTagValues':
                return $this->refactorGetClassTagValues($node);
            case 'getMethodTagsValues':
                return $this->refactorGetMethodTagsValues($node);
            case self::HAS_METHOD:
                return $this->refactorHasMethod($node);
            case 'getMethodParameters':
                return $this->refactorGetMethodParameters($node);
            case 'isClassTaggedWith':
                return $this->refactorIsClassTaggedWith($node);
            case 'isPropertyTaggedWith':
                return $this->refactorIsPropertyTaggedWith($node);
            default:
        }

        return null;
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
            $this->createArray([])
        )));

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmt ?? $node;
        $this->addNodeBeforeNode($propertyTagsValuesNode, $positionNode);

        return $propertyTagsValuesVariable;
    }

    private function refactorGetClassPropertyNamesMethod(MethodCall $node): Node
    {
        return $this->createFuncCall(
            'array_keys',
            [$this->createMethodCall($this->createClassSchema($node), 'getProperties')]
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
            $this->createArray([])
        );
    }

    private function createArrayDimFetchTags(MethodCall $node): ArrayDimFetch
    {
        return new ArrayDimFetch(
            $this->createMethodCall($this->createClassSchema($node), 'getProperty', [$node->args[1]->value]),
            new String_(self::TAGS)
        );
    }

    private function refactorGetClassTagsValues(MethodCall $node): MethodCall
    {
        return $this->createMethodCall($this->createClassSchema($node), 'getTags');
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
            $this->createArray([])
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
            $this->createMethodCall($this->createClassSchema($node), 'getMethod', [$node->args[1]->value]),
            new String_(self::TAGS)
        ), $this->createArray([]));
    }

    private function refactorHasMethod(MethodCall $node): ?Node
    {
        if (! isset($node->args[1])) {
            return null;
        }

        return $this->createMethodCall($this->createClassSchema($node), self::HAS_METHOD, [$node->args[1]->value]);
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
            $this->createMethodCall($this->createClassSchema($node), 'getMethod', [$node->args[1]->value]),
            new String_('params')
        ), $this->createArray([]));
    }

    private function refactorIsPropertyTaggedWith(MethodCall $node): ?Node
    {
        if (! isset($node->args[1], $node->args[2])) {
            return null;
        }

        $propertyVariable = new Variable('propertyReflectionService');
        $propertyNode = new Expression(new Assign($propertyVariable, $this->createMethodCall(
            $this->createMethodCall($node->var, 'getClassSchema', [$node->args[0]->value]),
            'getProperty',
            [$node->args[1]->value]
        )));

        $currentStmt = $node->getAttribute(AttributeKey::CURRENT_STATEMENT);
        $positionNode = $currentStmt ?? $node;
        $this->addNodeBeforeNode($propertyNode, $positionNode);

        return new Ternary(
            new Empty_($propertyVariable),
            $this->createFalse(),
            new Isset_([
                new ArrayDimFetch(new ArrayDimFetch($propertyVariable, new String_(self::TAGS)), $node->args[2]->value),
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

        return new Bool_($this->createFuncCall('count', [
            $this->createFuncCall('array_filter', [
                $this->createFuncCall('array_keys', [$this->refactorGetClassTagsValues($node)]),
                $anonymousFunction,
            ]),
        ]
        ));
    }

    private function createClassSchema(MethodCall $node): MethodCall
    {
        return $this->createMethodCall($node->var, 'getClassSchema', [$node->args[0]->value]);
    }
}
