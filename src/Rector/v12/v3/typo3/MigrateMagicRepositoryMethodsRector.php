<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v3\typo3;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.3/Deprecation-100071-MagicRepositoryFindByMethods.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\MigrateMagicRepositoryMethodsRectorTest
 */
final class MigrateMagicRepositoryMethodsRector extends AbstractRector
{
    private ReflectionResolver $reflectionResolver;

    public function __construct(ReflectionResolver $reflectionResolver)
    {
        $this->reflectionResolver = $reflectionResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Repository')
        )) {
            return null;
        }

        $methodName = $this->getName($node->name);

        if ($methodName === null) {
            return null;
        }

        if (! \str_starts_with($methodName, 'findBy')
            && ! \str_starts_with($methodName, 'findOneBy')
            && ! \str_starts_with($methodName, 'countBy')
        ) {
            return null;
        }

        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node);
        # Class reflection only works when the method exists - thus we don't migrate if it succeeds/is not null
        if ($methodReflection instanceof MethodReflection) {
            return null;
        }

        $propertyName = '';
        $newMethodCall = '';

        if (\str_starts_with($methodName, 'findBy')) {
            $propertyName = str_replace('findBy', '', $methodName);
            $newMethodCall = 'findBy';
        }

        if (\str_starts_with($methodName, 'findOneBy')) {
            $propertyName = str_replace('findOneBy', '', $methodName);
            $newMethodCall = 'findOneBy';
        }

        if (\str_starts_with($methodName, 'countBy')) {
            $propertyName = str_replace('countBy', '', $methodName);
            $newMethodCall = 'count';
        }

        if ($propertyName === '' || $newMethodCall === '') {
            return null;
        }

        $newArgs = new Array_([new ArrayItem($node->args[0]->value, new String_(lcfirst($propertyName)))]);

        return $this->nodeFactory->createMethodCall($node->var, $newMethodCall, [$newArgs->items]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the magic findBy methods', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$blogRepository->findByFooBar('bar');
$blogRepository->findOneByFoo('bar');
$blogRepository->countByFoo('bar');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$blogRepository->findBy(['fooBar' => 'bar']);
$blogRepository->findOneBy(['foo' => 'bar']);
$blogRepository->count(['foo' => 'bar']);
CODE_SAMPLE
            ),
        ]);
    }
}
