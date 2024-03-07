<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddPropertyTypeDeclarationRector::class, [
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
};
