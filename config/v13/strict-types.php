<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
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
        ]
    );
};
