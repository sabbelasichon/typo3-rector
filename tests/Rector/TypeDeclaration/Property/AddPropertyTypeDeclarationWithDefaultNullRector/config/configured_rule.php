<?php

declare(strict_types=1);

use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Ssch\TYPO3Rector\Tests\Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector\Source\ParentClassWithProperty;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddPropertyTypeDeclarationWithDefaultNullRector::class, [
            new AddPropertyTypeDeclaration(ParentClassWithProperty::class, 'name', TypeCombinator::addNull(
                new StringType()
            )),
        ]);
};
