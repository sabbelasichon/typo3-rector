<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\Source\ParentClassWithProperty;

final class SomeClass extends ParentClassWithProperty
{
    public $name;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\Source\ParentClassWithProperty;

final class SomeClass extends ParentClassWithProperty
{
    public ?string $name = null;
}

?>
