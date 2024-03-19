<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\VoidType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceInterface;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
        // TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
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
        // TYPO3\CMS\Extbase\Mvc\Controller\ActionController
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'responseFactory',
            new ObjectType('Psr\Http\Message\ResponseFactoryInterface')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'streamFactory',
            new ObjectType('Psr\Http\Message\StreamFactoryInterface')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'reflectionService',
            new ObjectType('TYPO3\CMS\Extbase\Reflection\ReflectionService')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'hashService',
            new ObjectType('TYPO3\CMS\Extbase\Security\Cryptography\HashService')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'mvcPropertyMappingConfigurationService',
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'eventDispatcher',
            new ObjectType('Psr\EventDispatcher\EventDispatcherInterface')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'uriBuilder',
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'validatorResolver',
            new ObjectType('TYPO3\CMS\Extbase\Validation\ValidatorResolver')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'arguments',
            new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\Arguments')
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'configurationManager',
            new ObjectType('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface')
        ),

        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'defaultViewObjectName',
            new StringType()
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'errorMethodName',
            new StringType()
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'actionMethodName',
            new StringType()
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\Mvc\Controller\ActionController',
            'settings',
            new ArrayType(new MixedType(), new MixedType())
        ),
    ]);
    // TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject
    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationWithDefaultNullRector::class, [
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject',
            'uid',
            TypeCombinator::addNull(new IntegerType())
        ),
        new AddPropertyTypeDeclaration(
            'TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject',
            'pid',
            TypeCombinator::addNull(new IntegerType())
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\FileInterface',
                'setContents',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'rename', 0, new StringType()),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\FileInterface',
                'getForLocalProcessing',
                0,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\FileInterface',
                'hasProperty',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getProperty', 0, new StringType()),
            // TYPO3\CMS\Core\Resource\Driver\DriverInterface
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'setStorageUid',
                0,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'mergeConfigurationCapabilities',
                0,
                new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hasCapability',
                0,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'sanitizeFileName',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'sanitizeFileName',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hashIdentifier',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getParentFolderIdentifierOfIdentifier',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getPublicUrl',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFolder',
                2,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'deleteFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'deleteFolder',
                1,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'fileExists',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'folderExists',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isFolderEmpty',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'addFile',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'addFile',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'addFile',
                2,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'addFile',
                3,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFile',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFile',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFileWithinStorage',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFileWithinStorage',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFileWithinStorage',
                2,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFile',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFile',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'replaceFile',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'replaceFile',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'deleteFile',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hash',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hash',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFileWithinStorage',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFileWithinStorage',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFileWithinStorage',
                2,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFolderWithinStorage',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFolderWithinStorage',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFolderWithinStorage',
                2,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFolderWithinStorage',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFolderWithinStorage',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFolderWithinStorage',
                2,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileContents',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'setFileContents',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'setFileContents',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'fileExistsInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'fileExistsInFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'folderExistsInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'folderExistsInFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileForLocalProcessing',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileForLocalProcessing',
                1,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getPermissions',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'dumpFileContents',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isWithin',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isWithin',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInfoByIdentifier',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInfoByIdentifier',
                1,
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                1,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                2,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                3,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                5,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                6,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFolderInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFolderInFolder',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                1,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                2,
                new IntegerType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                3,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                5,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                6,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFilesInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFilesInFolder',
                1,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFoldersInFolder',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFoldersInFolder',
                1,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFolderInfoByIdentifier',
                0,
                new StringType()
            ),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration(FileInterface::class, 'hasProperty', new BooleanType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getProperty', new MixedType(true)),
            new AddReturnTypeDeclaration(FileInterface::class, 'getSize', new IntegerType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getModificationTime', new IntegerType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getCreationTime', new IntegerType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getSha1', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getNameWithoutExtension', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getExtension', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getContents', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getForLocalProcessing', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getMimeType', new StringType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'delete', new BooleanType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'isIndexed', new BooleanType()),
            new AddReturnTypeDeclaration(FileInterface::class, 'getPublicUrl', TypeCombinator::addNull(
                new StringType()
            )),
            new AddReturnTypeDeclaration(FileInterface::class, 'toArray', new ArrayType(
                new MixedType(),
                new MixedType()
            )),
            new AddReturnTypeDeclaration(FileInterface::class, 'rename', new ObjectType(
                'TYPO3\CMS\Core\Resource\FileInterface'
            )),
            new AddReturnTypeDeclaration(
                FileInterface::class,
                'setContents',
                new \Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType(
                    'TYPO3\CMS\Core\Resource\FileInterface'
                )
            ),
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
            // TYPO3\CMS\Core\Resource\Driver\DriverInterface
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'processConfiguration',
                new VoidType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'setStorageUid',
                new VoidType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'initialize',
                new VoidType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getCapabilities',
                new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'mergeConfigurationCapabilities',
                new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hasCapability',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isCaseSensitiveFileSystem',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'sanitizeFileName',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hashIdentifier',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getRootLevelFolder',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getDefaultFolder',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getParentFolderIdentifierOfIdentifier',
                TypeCombinator::addNull(new StringType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getPublicUrl',
                TypeCombinator::addNull(new StringType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFolder',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFolder',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'deleteFolder',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'fileExists',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'folderExists',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isFolderEmpty',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'addFile',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'createFile',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFileWithinStorage',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'renameFile',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'replaceFile',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'deleteFile',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'hash',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFileWithinStorage',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'moveFolderWithinStorage',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'copyFolderWithinStorage',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileContents',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'setFileContents',
                new IntegerType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'fileExistsInFolder',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'folderExistsInFolder',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileForLocalProcessing',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getPermissions',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'dumpFileContents',
                new VoidType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'isWithin',
                new BooleanType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInfoByIdentifier',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFolderInfoByIdentifier',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFileInFolder',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFilesInFolder',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFolderInFolder',
                new StringType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'getFoldersInFolder',
                new ArrayType(new MixedType(), new MixedType())
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFilesInFolder',
                new IntegerType()
            ),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Core\Resource\Driver\DriverInterface',
                'countFoldersInFolder',
                new IntegerType()
            ),
        ]
    );
};
