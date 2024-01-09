<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/9.4/Deprecation-85004-DeprecateMethodsInReflectionService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector\UseClassSchemaInsteadReflectionServiceMethodsRectorTest
 */
final class UseClassSchemaInsteadReflectionServiceMethodsRector extends AbstractRector implements MinPhpVersionInterface
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
            'getPropertyTagValues',
            'getClassTagsValues',
            'getClassTagValues',
            'getMethodTagsValues',
            self::HAS_METHOD,
            'getMethodParameters',
        ])) {
            return null;
        }

        if ($node->args === []) {
            return null;
        }

        $nodeName = $this->getName($node->name);

        if ($nodeName === null) {
            return null;
        }

        if ($nodeName === 'getClassPropertyNames') {
            return $this->refactorGetClassPropertyNamesMethod($node);
        }

        if ($nodeName === 'getPropertyTagValues') {
            return $this->refactorGetPropertyTagValuesMethod($node);
        }

        if ($nodeName === 'getClassTagsValues') {
            return $this->refactorGetClassTagsValues($node);
        }

        if ($nodeName === 'getClassTagValues') {
            return $this->refactorGetClassTagValues($node);
        }

        if ($nodeName === 'getMethodTagsValues') {
            return $this->refactorGetMethodTagsValues($node);
        }

        if ($nodeName === self::HAS_METHOD) {
            return $this->refactorHasMethod($node);
        }

        return $this->refactorGetMethodParameters($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULL_COALESCE;
    }

    private function refactorGetClassPropertyNamesMethod(MethodCall $methodCall): Node
    {
        return $this->nodeFactory->createFuncCall(
            'array_keys',
            [$this->nodeFactory->createMethodCall($this->createClassSchema($methodCall), 'getProperties')]
        );
    }

    private function refactorGetPropertyTagValuesMethod(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[1])) {
            return null;
        }

        if (! isset($methodCall->args[2])) {
            return null;
        }

        return new Coalesce(
            new ArrayDimFetch($this->createArrayDimFetchTags($methodCall), $methodCall->args[2]->value),
            $this->nodeFactory->createArray([])
        );
    }

    private function createArrayDimFetchTags(MethodCall $methodCall): ArrayDimFetch
    {
        return new ArrayDimFetch(
            $this->nodeFactory->createMethodCall(
                $this->createClassSchema($methodCall),
                'getProperty',
                [$methodCall->args[1]->value]
            ),
            new String_(self::TAGS)
        );
    }

    private function refactorGetClassTagsValues(MethodCall $methodCall): MethodCall
    {
        return $this->nodeFactory->createMethodCall($this->createClassSchema($methodCall), 'getTags');
    }

    private function refactorGetClassTagValues(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[1])) {
            return null;
        }

        return new Coalesce(
            new ArrayDimFetch($this->refactorGetClassTagsValues($methodCall), $methodCall->args[1]->value),
            $this->nodeFactory->createArray([])
        );
    }

    private function refactorGetMethodTagsValues(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[1])) {
            return null;
        }

        return new Coalesce(new ArrayDimFetch(
            $this->nodeFactory->createMethodCall(
                $this->createClassSchema($methodCall),
                'getMethod',
                [$methodCall->args[1]->value]
            ),
            new String_(self::TAGS)
        ), $this->nodeFactory->createArray([]));
    }

    private function refactorHasMethod(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[1])) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->createClassSchema($methodCall),
            self::HAS_METHOD,
            [$methodCall->args[1]->value]
        );
    }

    private function refactorGetMethodParameters(MethodCall $methodCall): ?Node
    {
        if (! isset($methodCall->args[1])) {
            return null;
        }

        return new Coalesce(new ArrayDimFetch(
            $this->nodeFactory->createMethodCall(
                $this->createClassSchema($methodCall),
                'getMethod',
                [$methodCall->args[1]->value]
            ),
            new String_('params')
        ), $this->nodeFactory->createArray([]));
    }

    private function createClassSchema(MethodCall $methodCall): MethodCall
    {
        return $this->nodeFactory->createMethodCall($methodCall->var, 'getClassSchema', [$methodCall->args[0]->value]);
    }
}
