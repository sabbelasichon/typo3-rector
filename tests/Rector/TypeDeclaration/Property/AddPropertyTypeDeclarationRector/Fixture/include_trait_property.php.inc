<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\SomeTraitWithProperty;

final class IncludeTraitProperty
{
    use SomeTraitWithProperty;

    public $value;
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\SomeTraitWithProperty;

final class IncludeTraitProperty
{
    use SomeTraitWithProperty;

    public int $value;
}

?>
