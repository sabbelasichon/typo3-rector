<?php

declare(strict_types=1);

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\BooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector;
use Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultValueRector;
use Ssch\TYPO3Rector\TypeDeclaration\ValueObject\AddPropertyTypeWithDefaultValueDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationRector::class,
        [
            new AddPropertyTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'propertyMappingConfiguration',
                new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration')
            ),
            new AddPropertyTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'validationResults',
                new ObjectType('TYPO3\CMS\Extbase\Error\Result')
            ),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationWithDefaultValueRector::class,
        [
            new AddPropertyTypeWithDefaultValueDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'name',
                new StringType(),
                new String_('')
            ),
            new AddPropertyTypeWithDefaultValueDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'shortName',
                new StringType(),
                new String_('')
            ),
            new AddPropertyTypeWithDefaultValueDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'dataType',
                new StringType(),
                new String_('')
            ),
            new AddPropertyTypeWithDefaultValueDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'isRequired',
                new BooleanType(),
                new ConstFetch(new Name('false'))
            ),
            new AddPropertyTypeWithDefaultValueDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'hasBeenValidated',
                new BooleanType(),
                new ConstFetch(new Name('false'))
            ),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddPropertyTypeDeclarationWithDefaultNullRector::class,
        [
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'value', new MixedType(true)),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'defaultValue', new MixedType(
                true
            )),
            new AddPropertyTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'validator', new UnionType([
                new NullType(),
                new ObjectType('TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface'),
            ])),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddParamTypeDeclarationRector::class,
        [
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                '__construct',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                '__construct',
                1,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'setShortName',
                0,
                new StringType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'setRequired',
                0,
                new BooleanType()
            ),
            new AddParamTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'setDefaultValue',
                0,
                new MixedType(true)
            ),
            new AddParamTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setValue', 0, new MixedType(
                true
            )),
        ]
    );

    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getName', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setShortName', new ObjectType(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument'
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getShortName', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getDataType', new StringType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setRequired', new ObjectType(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument'
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'isRequired', new BooleanType()),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setDefaultValue', new ObjectType(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument'
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getDefaultValue', new MixedType(
                true
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setValidator', new ObjectType(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument'
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getValidator', new UnionType([
                new NullType(),
                new ObjectType('TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface'),
            ])),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'setValue', new ObjectType(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument'
            )),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', 'getValue', new MixedType(true)),
            new AddReturnTypeDeclaration(
                'TYPO3\CMS\Extbase\Mvc\Controller\Argument',
                'getPropertyMappingConfiguration',
                new ObjectType('TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration')
            ),
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Mvc\Controller\Argument', '__toString', new StringType()),
        ]
    );
};
