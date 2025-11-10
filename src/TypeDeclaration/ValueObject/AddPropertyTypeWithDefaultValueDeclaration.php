<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TypeDeclaration\ValueObject;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Scalar\Float_;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

/**
 * A PropertyTyp declaration with an additional default value
 * @see \Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration
 */
final class AddPropertyTypeWithDefaultValueDeclaration
{
    /**
     * @readonly
     */
    private string $class;

    /**
     * @readonly
     */
    private string $propertyName;

    /**
     * @readonly
     */
    private Type $type;

    /**
     * @var String_|ClassConstFetch|ConstFetch|Int_|Float_
     * @readonly
     */
    private Expr $defaultValue;

    public function __construct(string $class, string $propertyName, Type $type, Expr $defaultValue)
    {
        $this->class = $class;
        $this->propertyName = $propertyName;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        RectorAssert::className($class);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getDefaultValue(): Expr
    {
        return $this->defaultValue;
    }
}
