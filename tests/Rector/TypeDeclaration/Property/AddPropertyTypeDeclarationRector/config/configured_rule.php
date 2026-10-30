<?php

declare(strict_types=1);

use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\ParentClassWithProperty;
use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector\Source\SomeTraitWithProperty;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
            new AddPropertyTypeDeclaration(ParentClassWithProperty::class, 'name', new StringType()),
            new AddPropertyTypeDeclaration(SomeTraitWithProperty::class, 'value', new IntegerType()),
        ]);
};
