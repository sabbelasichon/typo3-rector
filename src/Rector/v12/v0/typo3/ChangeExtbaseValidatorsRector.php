<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0\typo3;

use PhpParser\Builder\Method;
use PhpParser\Builder\Param;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Enum\ObjectReference;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96998-ExtbaseValidatorInterfaceChanged.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\ChangeExtbaseValidatorsRectorTest
 */
final class ChangeExtbaseValidatorsRector extends AbstractScopeAwareRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope)
    {
        $classReflection = $scope->getClassReflection();

        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (! $classReflection->implementsInterface('TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface')) {
            return null;
        }

        $isSubClassOfAbstractValidator = $classReflection->isSubclassOf(
            'TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator'
        );

        $assignToOptionsProperty = $this->manipulateConstructor($node);

        // Add setOptions method only if validator is not already subclass of AbstractValidator
        $setOptionsClassMethod = $node->getMethod('setOptions');
        if (! $setOptionsClassMethod instanceof ClassMethod && ! $isSubClassOfAbstractValidator) {
            $node->stmts[] = $this->createSetOptionsClassMethod($assignToOptionsProperty);
        }

        if ($isSubClassOfAbstractValidator) {
            $this->manipulateIsValidMethod($node);
        }

        $this->manipulateValidateMethod($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adapt extbase validators to new interface', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class MyCustomValidatorWithOptions implements ValidatorInterface
{
    private array $options;
    private \MyDependency $myDependency;

    public function __construct(array $options, \MyDependency $myDependency)
    {
        $this->options = $options;
        $this->myDependency = $myDependency;
    }

    public function validate($value)
    {
        // Do something
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;
use TYPO3\CMS\Extbase\Validation\ValidatorResolver\ValidatorResolver;

final class MyCustomValidatorWithoutOptions implements ValidatorInterface
{
    private array $options;
    private \MyDependency $myDependency;

    public function __construct(\MyDependency $myDependency)
    {
        $this->myDependency = $myDependency;
    }

    public function validate($value)
    {
        // Do something
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    private function createSetOptionsClassMethod(bool $assignToOptionsProperty): ClassMethod
    {
        $paramBuilder = new Param('options');
        $paramBuilder->setType('array');

        $methodBuilder = new Method('setOptions');
        $methodBuilder->makePublic();
        $methodBuilder->addParam($paramBuilder->getNode());
        $methodBuilder->setReturnType('void');

        $methodNode = $methodBuilder->getNode();

        if ($assignToOptionsProperty) {
            $methodNode->stmts[] = new Expression($this->nodeFactory->createPropertyAssignment('options'));
        }

        return $methodNode;
    }

    private function shouldKeepConstructorStatement(Stmt $constructorStmt): bool
    {
        if (! $constructorStmt instanceof Expression) {
            return true;
        }

        if (! $constructorStmt->expr instanceof Assign && ! $constructorStmt->expr instanceof StaticCall) {
            return true;
        }

        if ($constructorStmt->expr instanceof StaticCall) {
            return $this->shouldKeepStaticCall($constructorStmt->expr);
        }

        return $this->shouldKeepAssignment($constructorStmt->expr);
    }

    private function manipulateConstructor(Class_ $node): bool
    {
        $constructorMethod = $node->getMethod(MethodName::CONSTRUCT);

        if (! $constructorMethod instanceof ClassMethod) {
            return false;
        }

        $assignToOptionsProperty = false;
        $constructorParams = [];
        foreach ($constructorMethod->getParams() as $param) {
            if ($this->nodeNameResolver->isName($param, 'options')) {
                $assignToOptionsProperty = true;
                continue;
            }

            $constructorParams[] = $param;
        }

        $constructorStatementsToKeep = [];
        if (is_array($constructorMethod->stmts)) {
            foreach ($constructorMethod->stmts as $constructorStmt) {
                if ($this->shouldKeepConstructorStatement($constructorStmt)) {
                    $constructorStatementsToKeep[] = $constructorStmt;
                }
            }
        }

        $constructorMethod->params = $constructorParams;
        $constructorMethod->stmts = $constructorStatementsToKeep;

        return $assignToOptionsProperty;
    }

    private function shouldKeepAssignment(Assign $assign): bool
    {
        if (! $assign->var instanceof PropertyFetch) {
            return true;
        }

        return ! $this->nodeNameResolver->isName($assign->var, 'options');
    }

    private function shouldKeepStaticCall(StaticCall $staticCall): bool
    {
        if (! $staticCall->class instanceof Name) {
            return true;
        }

        return ! $this->isName($staticCall->class, ObjectReference::PARENT);
    }

    private function manipulateIsValidMethod(Class_ $node): void
    {
        $isValidClassMethod = $node->getMethod('isValid');

        if (! $isValidClassMethod instanceof ClassMethod) {
            return;
        }

        if ($isValidClassMethod->returnType !== null) {
            return;
        }

        $isValidClassMethod->returnType = new Identifier('void');
    }

    private function manipulateValidateMethod(Class_ $node): void
    {
        $validateClassMethod = $node->getMethod('validate');

        if (! $validateClassMethod instanceof ClassMethod) {
            return;
        }

        if ($validateClassMethod->returnType !== null) {
            return;
        }

        $validateClassMethod->returnType = new FullyQualified('TYPO3\CMS\Extbase\Error\Result');
    }
}
