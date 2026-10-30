<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\ParentClassWithProperty;

final class SomeClass extends ParentClassWithProperty
{
    public $name;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\ParentClassWithProperty;

final class SomeClass extends ParentClassWithProperty
{
    public string $name;
}

?>
