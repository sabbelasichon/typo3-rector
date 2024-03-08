<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector;

return static function (RectorConfig $rectorConfig): void {
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
};
