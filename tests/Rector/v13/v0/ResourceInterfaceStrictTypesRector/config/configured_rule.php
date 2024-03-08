<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use TYPO3\CMS\Core\Resource\ResourceInterface;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../../../../../../config/config.php');
    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface',
            'hasProperty',
            0,
            new StringType())]
    );
    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface',
            'getProperty',
            0,
            new StringType())]
    );
    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration(ResourceInterface::class, 'getIdentifier', new StringType()),
            new AddReturnTypeDeclaration(ResourceInterface::class, 'getName', new StringType()),
            new AddReturnTypeDeclaration(
                ResourceInterface::class,
                'getStorage',
                new ObjectType('TYPO3\CMS\Core\Resource\ResourceStorage')
            ),
            new AddReturnTypeDeclaration(ResourceInterface::class, 'getHashedIdentifier', new StringType()),
            new AddReturnTypeDeclaration(
                ResourceInterface::class,
                'getParentFolder',
                new ObjectType('TYPO3\CMS\Core\Resource\FolderInterface')
            ),
        ]
    );
};
