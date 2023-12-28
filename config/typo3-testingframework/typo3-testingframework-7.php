<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
        new AddPropertyTypeDeclaration(
            'TYPO3\TestingFramework\Core\Unit\UnitTestCase',
            'resetSingletonInstances',
            new BooleanType()
        ),

        new AddPropertyTypeDeclaration(
            'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
            'coreExtensionsToLoad',
            new ArrayType(new StringType(), new StringType())
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
            'testExtensionsToLoad',
            new ArrayType(new StringType(), new StringType())
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
            'pathsToLinkInTestInstance',
            new ArrayType(new StringType(), new StringType())
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\TestingFramework\Core\Functional\FunctionalTestCase',
            'configurationToUseInTestInstance',
            new ArrayType(new StringType(), new StringType())
        ),
    ]);
};
