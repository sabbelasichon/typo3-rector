<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-105686-AvoidObsoleteCharsetInSanitizeFileName.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\MigrateObsoleteCharsetInSanitizeFileNameRectorTest
 */
final class MigrateObsoleteCharsetInSanitizeFileNameRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the second charset parameter from sanitizeFileName method in DriverInterface implementations',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

class MyDriver implements DriverInterface
{
    public function sanitizeFileName(string $fileName, string $charset = ''): string
    {
    }
}

class SomeClass
{
    public function doSomething(DriverInterface $driver)
    {
        $sanitizedName = $driver->sanitizeFileName('example.txt', 'utf-8');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

class MyDriver implements DriverInterface
{
    public function sanitizeFileName(string $fileName): string
    {
    }
}

class SomeClass
{
    public function doSomething(DriverInterface $driver)
    {
        $sanitizedName = $driver->sanitizeFileName('example.txt');
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class, ClassMethod::class];
    }

    /**
     * @param MethodCall|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        return $this->refactorClassMethod($node);
    }

    private function refactorMethodCall(MethodCall $methodCall): ?MethodCall
    {
        if (! $this->isName($methodCall->name, 'sanitizeFileName')) {
            return null;
        }

        if (! $this->isObjectType($methodCall->var, new ObjectType('TYPO3\CMS\Core\Resource\Driver\DriverInterface'))) {
            return null;
        }

        if (count($methodCall->args) <= 1) {
            return null;
        }

        // Remove the second argument
        $newArgs = [$methodCall->getArgs()[0]];
        $methodCall->args = $newArgs;

        return $methodCall;
    }

    private function refactorClassMethod(ClassMethod $classMethod): ?ClassMethod
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $classMethod,
            new ObjectType('TYPO3\CMS\Core\Resource\Driver\DriverInterface')
        )) {
            return null;
        }

        if (! $this->isName($classMethod->name, 'sanitizeFileName')) {
            return null;
        }

        if (count($classMethod->params) <= 1) {
            return null;
        }

        unset($classMethod->params[1]);
        return $classMethod;
    }
}
