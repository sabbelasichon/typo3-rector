<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\Config\RectorConfig;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Rector\ValueObject\PhpVersionFeature;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationRector::class,
        [
            // TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'newObjects', new ArrayType(new MixedType(), new MixedType())),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'changedObjects', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'addedObjects', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'removedObjects', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\ObjectStorage')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'queryFactory', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\QueryFactoryInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'backend', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\BackendInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'persistenceSession', new ObjectType('TYPO3\CMS\Extbase\Persistence\Generic\Session')),
            // TYPO3\CMS\Extbase\Mvc\Controller\ActionController
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'responseFactory', new ObjectType('Psr\Http\Message\ResponseFactoryInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'streamFactory', new ObjectType('Psr\Http\Message\StreamFactoryInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'reflectionService', new ObjectType('TYPO3\CMS\Extbase\Reflection\ReflectionService')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'hashService', new ObjectType('TYPO3\CMS\Extbase\Security\Cryptography\HashService')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'mvcPropertyMappingConfigurationService', new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfigurationService')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'eventDispatcher', new ObjectType('Psr\EventDispatcher\EventDispatcherInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'uriBuilder', new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'validatorResolver', new ObjectType('TYPO3\CMS\Extbase\Validation\ValidatorResolver')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'arguments', new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\Arguments')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'configurationManager', new ObjectType('TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface')),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'defaultViewObjectName', new StringType()),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'errorMethodName', new StringType()),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'actionMethodName', new StringType()),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'settings', new ArrayType(new MixedType(), new MixedType())),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationWithDefaultNullRector::class,
        [
            // TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject', 'uid', new UnionType([new NullType(), new IntegerType()])),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject', 'pid', new UnionType([new NullType(), new IntegerType()])),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'setContents', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'rename', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getForLocalProcessing', 0, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'hasProperty', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getProperty', 0, new StringType()),
            // TYPO3\CMS\Core\Resource\Driver\DriverInterface
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'setStorageUid', 0, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'mergeConfigurationCapabilities', 0, new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hasCapability', 0, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'sanitizeFileName', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'sanitizeFileName', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hashIdentifier', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getParentFolderIdentifierOfIdentifier', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getPublicUrl', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFolder', 2, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'deleteFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'deleteFolder', 1, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'fileExists', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'folderExists', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isFolderEmpty', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'addFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'addFile', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'addFile', 2, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'addFile', 3, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFile', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFileWithinStorage', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFileWithinStorage', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFileWithinStorage', 2, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFile', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'replaceFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'replaceFile', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'deleteFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hash', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hash', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFileWithinStorage', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFileWithinStorage', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFileWithinStorage', 2, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFolderWithinStorage', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFolderWithinStorage', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFolderWithinStorage', 2, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFolderWithinStorage', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFolderWithinStorage', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFolderWithinStorage', 2, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileContents', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'setFileContents', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'setFileContents', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'fileExistsInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'fileExistsInFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'folderExistsInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'folderExistsInFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileForLocalProcessing', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileForLocalProcessing', 1, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getPermissions', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'dumpFileContents', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isWithin', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isWithin', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInfoByIdentifier', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInfoByIdentifier', 1, new ArrayType(new MixedType(), new MixedType())),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 1, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 2, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 3, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 5, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', 6, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFolderInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFolderInFolder', 1, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 1, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 2, new IntegerType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 3, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 5, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', 6, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFilesInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFilesInFolder', 1, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFoldersInFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFoldersInFolder', 1, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFolderInfoByIdentifier', 0, new StringType()),
            // TYPO3\CMS\Core\Resource\FolderInterface
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getSubfolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'hasFolder', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'hasFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getFile', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'rename', 0, new StringType()),
            // TYPO3\CMS\Extbase\Persistence\ObjectStorage
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetSet', 0, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetSet', 1, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetExists', 0, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetUnset', 0, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetGet', 0, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'contains', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'attach', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'attach', 1, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'detach', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'setInfo', 0, new MixedType(true)),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'isRelationDirty', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'getPosition', 0, new ObjectWithoutClassType()),
            // TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'initializeObject', 0, new VoidType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'registerNewObject', 0, new ObjectWithoutClassType()),
            // TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'isNewObject', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getIdentifierByObject', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectByIdentifier', 0, new UnionType([new StringType(), new IntegerType()])),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectByIdentifier', 1, new UnionType([new NullType(), new StringType()])),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectByIdentifier', 2, new BooleanType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'registerRepositoryClassName', 0, new StringType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'add', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'remove', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'update', 0, new ObjectWithoutClassType()),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'createQueryForType', 0, new StringType()),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'hasProperty', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getProperty', new MixedType(true)),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getSize', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getModificationTime', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getCreationTime', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getSha1', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getNameWithoutExtension', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getExtension', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getContents', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getForLocalProcessing', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getMimeType', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'delete', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'isIndexed', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'getPublicUrl', new UnionType([new NullType(), new StringType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'toArray', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'rename', new ObjectType('TYPO3\CMS\Core\Resource\FileInterface')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FileInterface', 'setContents', new SelfObjectType('TYPO3\CMS\Core\Resource\FileInterface')),
            // TYPO3\CMS\Core\Resource\ResourceInterface
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\ResourceInterface', 'getIdentifier', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\ResourceInterface', 'getName', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\ResourceInterface', 'getStorage', new ObjectType('TYPO3\CMS\Core\Resource\ResourceStorage')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\ResourceInterface', 'getHashedIdentifier', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\ResourceInterface', 'getParentFolder', new ObjectType('TYPO3\CMS\Core\Resource\FolderInterface')),
            // TYPO3\CMS\Core\Resource\Driver\DriverInterface
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'processConfiguration', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'setStorageUid', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'initialize', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getCapabilities', new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'mergeConfigurationCapabilities', new ObjectType('TYPO3\CMS\Core\Resource\Capabilities')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hasCapability', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isCaseSensitiveFileSystem', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'sanitizeFileName', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hashIdentifier', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getRootLevelFolder', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getDefaultFolder', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getParentFolderIdentifierOfIdentifier', new UnionType([new NullType(), new StringType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getPublicUrl', new UnionType([new NullType(), new StringType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFolder', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFolder', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'deleteFolder', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'fileExists', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'folderExists', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isFolderEmpty', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'addFile', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'createFile', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFileWithinStorage', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'renameFile', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'replaceFile', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'deleteFile', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'hash', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFileWithinStorage', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'moveFolderWithinStorage', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'copyFolderWithinStorage', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileContents', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'setFileContents', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'fileExistsInFolder', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'folderExistsInFolder', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileForLocalProcessing', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getPermissions', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'dumpFileContents', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'isWithin', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInfoByIdentifier', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFolderInfoByIdentifier', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFileInFolder', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFilesInFolder', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFolderInFolder', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'getFoldersInFolder', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFilesInFolder', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\Driver\DriverInterface', 'countFoldersInFolder', new IntegerType()),
            // TYPO3\CMS\Core\Resource\FolderInterface
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getSubfolders', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getSubfolder', new ObjectType('TYPO3\CMS\Core\Resource\Folder')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'hasFolder', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'hasFile', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getFile', new UnionType([new NullType(), new ObjectType('TYPO3\CMS\Core\Resource\FileInterface')])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'rename', new SelfObjectType('TYPO3\CMS\Core\Resource\FolderInterface')),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'delete', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getModificationTime', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Resource\FolderInterface', 'getCreationTime', new IntegerType()),
            // TYPO3\CMS\Extbase\Mvc\Controller\ActionController
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'initializeAction', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'addErrorFlashMessage', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'getErrorFlashMessage', new UnionType([new BooleanType(), new StringType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\ActionController', 'getFlattenedValidationErrorMessage', new StringType()),
            // TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface', 'setPid', new VoidType()),
            // TYPO3\CMS\Extbase\Persistence\ObjectStorage
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'current', new UnionType([new NullType(), new ObjectWithoutClassType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'offsetGet', new MixedType(true)),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'contains', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'attach', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'detach', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'getInfo', new MixedType(true)),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'addAll', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'toArray', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'getArray', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'isRelationDirty', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\ObjectStorage', 'getPosition', new UnionType([new NullType(), new IntegerType()])),
            // TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'initializeObject', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager', 'registerNewObject', new VoidType()),
            // TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'persistAll', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'clearState', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'isNewObject', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getIdentifierByObject', new UnionType([new NullType(), new StringType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectByIdentifier', new UnionType([new NullType(), new ObjectWithoutClassType()])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectCountByQuery', new IntegerType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'getObjectDataByQuery', new ArrayType(new MixedType(), new MixedType())),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'registerRepositoryClassName', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'add', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'remove', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'update', new VoidType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface', 'createQueryForType', new ObjectType('TYPO3\CMS\Extbase\Persistence\QueryInterface')),
        ]
    );
};
