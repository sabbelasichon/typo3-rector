<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'newObjects',
            new ArrayType(new MixedType(), new MixedType())
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'changedObjects',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'addedObjects',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'removedObjects',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'queryFactory',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\QueryFactoryInterface')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'backend',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\BackendInterface')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager',
            'persistenceSession',
            new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\Session')
        ),
    ]);
};
