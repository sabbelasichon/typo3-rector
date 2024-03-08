<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use TYPO3\CMS\Core\Resource\FileInterface;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface',
            'hasProperty',
            0,
            new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface',
                'getProperty',
                0,
                new StringType())
        ]
    );
    $rectorConfig->phpVersion(\Rector\ValueObject\PhpVersionFeature::MIXED_TYPE);

    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration(FileInterface::class, 'hasProperty', new BooleanType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getProperty', new MixedType(true)),
        ]
    );
};
