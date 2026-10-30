<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\ParentClassWithProperty;

final class SkipAlreadyAddedType extends ParentClassWithProperty
{
    public int $name;
}
