<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

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
use PHPStan\Reflection\ClassReflection;
use Rector\Enum\ObjectReference;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96998-ExtbaseValidatorInterfaceChanged.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector\ChangeExtbaseValidatorsRectorTest
 */
final class ChangeExtbaseValidatorsRector extends AbstractRector implements DocumentedRuleInterface
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
    public function refactor(Node $node): ?Class_
    {
        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();

        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        if (! $classReflection->implementsInterface('TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface')) {
            return null;
        }

        $isSubClassOfAbstractValidator = $classReflection->is(
            'TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator'
        );

        $hasChanged = false;

        $assignToOptionsProperty = $this->manipulateConstructor($node, $hasChanged);

        // Add setOptions method only if validator is not already subclass of AbstractValidator
        $setOptionsClassMethod = $node->getMethod('setOptions');
        if (! $setOptionsClassMethod instanceof ClassMethod && ! $isSubClassOfAbstractValidator) {
            $node->stmts[] = $this->createSetOptionsClassMethod($assignToOptionsProperty);
            $hasChanged = true;
        }

        if ($isSubClassOfAbstractValidator && $this->manipulateIsValidMethod($node)) {
            $hasChanged = true;
        }

        if ($this->manipulateValidateMethod($node)) {
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Adapt extbase validators to new interface', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

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

    private function manipulateConstructor(Class_ $node, bool &$hasChanged): bool
    {
        $constructorMethod = $node->getMethod(MethodName::CONSTRUCT);

        if (! $constructorMethod instanceof ClassMethod) {
            return false;
        }

        $assignToOptionsProperty = false;
        $constructorParams = [];
        foreach ($constructorMethod->getParams() as $param) {
            if ($this->isName($param, 'options')) {
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

        if (count($constructorMethod->params) !== count($constructorParams)) {
            $constructorMethod->params = $constructorParams;
            $hasChanged = true;
        }

        if (is_array($constructorMethod->stmts)
            && count($constructorMethod->stmts) !== count($constructorStatementsToKeep)
        ) {
            $constructorMethod->stmts = $constructorStatementsToKeep;
            $hasChanged = true;
        }

        return $assignToOptionsProperty;
    }

    private function shouldKeepAssignment(Assign $assign): bool
    {
        if (! $assign->var instanceof PropertyFetch) {
            return true;
        }

        return ! $this->isName($assign->var, 'options');
    }

    private function shouldKeepStaticCall(StaticCall $staticCall): bool
    {
        if (! $staticCall->class instanceof Name) {
            return true;
        }

        return ! $this->isName($staticCall->class, ObjectReference::PARENT);
    }

    private function manipulateIsValidMethod(Class_ $node): bool
    {
        $isValidClassMethod = $node->getMethod('isValid');

        if (! $isValidClassMethod instanceof ClassMethod) {
            return false;
        }

        if ($isValidClassMethod->returnType instanceof Node) {
            return false;
        }

        $isValidClassMethod->returnType = new Identifier('void');
        return true;
    }

    private function manipulateValidateMethod(Class_ $node): bool
    {
        $validateClassMethod = $node->getMethod('validate');

        if (! $validateClassMethod instanceof ClassMethod) {
            return false;
        }

        if ($validateClassMethod->returnType instanceof Node) {
            return false;
        }

        $validateClassMethod->returnType = new FullyQualified('TYPO3\CMS\Extbase\Error\Result');
        return true;
    }
}
