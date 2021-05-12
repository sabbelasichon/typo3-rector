<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Namespace_\RenameNamespaceRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector;
use Ssch\TYPO3Rector\Rector\v10\v4\RemoveFormatConstantsEmailFinisherRector;
use Ssch\TYPO3Rector\Yaml\Form\Rector\EmailFinisherRectorInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../config.php');
    $services = $containerConfigurator->services();
    $services->set(RemovePropertyExtensionNameRector::class);
    $services->set(UseNativePhpHex2binMethodRector::class);
    $services->set(RefactorIdnaEncodeMethodToNativeFunctionRector::class);
    $services->set('rename_namespace_backend_controller_file_to_filelist_controller_file')
        ->class(RenameNamespaceRector::class)
        ->call(
            'configure',
            [
                [
                    RenameNamespaceRector::OLD_TO_NEW_NAMESPACES => [
                        'TYPO3\CMS\Backend\Controller\File' => 'TYPO3\CMS\Filelist\Controller\File',

                    ],
                ],
            ]
        );
    $services->set(UseMetaDataAspectRector::class);
    $services->set(ForceTemplateParsingInTsfeAndTemplateServiceRector::class);
    $services->set(BackendUtilityGetViewDomainToPageRouterRector::class);
    $services->set(SetSystemLocaleFromSiteLanguageRector::class);
    $services->set(ConfigurationManagerAddControllerConfigurationMethodRector::class);
    $services->set(RemoveFormatConstantsEmailFinisherRector::class);
    $services->set(UseTwoLetterIsoCodeFromSiteLanguageRector::class);
    $services->set(UseControllerClassesInExtbasePluginsAndModulesRector::class);
    $services->set(ChangeDefaultCachingFrameworkNamesRector::class);
    $services->set(ExtEmConfRector::class)
        ->call('configure', [
            [
                ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
            ],
        ]);
    $services->set(SwiftMailerBasedMailMessageToMailerBasedMessageRector::class);

    $services->set('rename_database_record_list_thumb_code_backend_utility_thumb_code')
        ->class(MethodCallToStaticCallRector::class)
        ->call('configure', [
            [
                MethodCallToStaticCallRector::METHOD_CALLS_TO_STATIC_CALLS => ValueObjectInliner::inline([
                    new MethodCallToStaticCall(
                        'TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList',
                        'thumbCode',
                        'TYPO3\CMS\Backend\Utility\BackendUtility',
                        'thumbCode'
                    ),
                ]),
            ],
        ]);

    $services->set('rename_database_record_list_request_uri_to_list_url')
        ->class(RenameMethodRector::class)
        ->call('configure', [
            [
                RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
                    new MethodCallRename('TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList', 'requestUri', 'listURL'),
                ]),
            ],
        ]);

    $services->set(EmailFinisherRectorInterface::class);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->call('configure', [
            [
                AddReturnTypeDeclarationRector::METHOD_RETURN_TYPES => ValueObjectInliner::inline([
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface',
                        'getUid',
                        TypeCombinator::addNull(new IntegerType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface',
                        'getPid',
                        TypeCombinator::addNull(new IntegerType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface',
                        '_isNew',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface',
                        '_getProperties',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject',
                        'getUid',
                        TypeCombinator::addNull(new IntegerType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject',
                        'getPid',
                        TypeCombinator::addNull(new IntegerType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject',
                        '_isNew',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Service\ImageService',
                        'applyProcessingInstructions',
                        new ObjectType('TYPO3\CMS\Core\Resource\ProcessedFile')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Service\ImageService',
                        'getImageUri',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Service\ImageService', 'getImage', new ObjectType(
                        'TYPO3\CMS\Core\Resource\FileInterface'
                    )),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getSupportedSourceTypes',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getSupportedTargetType',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getTargetTypeForSource',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getPriority',
                        new IntegerType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'canConvertFrom',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getSourceChildPropertiesToBeConverted',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'getTypeOfChildProperty',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Property\TypeConverterInterface',
                        'convertFrom',
                        new MixedType()
                    ),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Error\Message', 'getMessage', new StringType()),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Error\Message', 'getCode', new IntegerType()),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Error\Message', 'getArguments', new ArrayType(
                        new MixedType(),
                        new MixedType()
                    )),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Error\Message', 'getTitle', new StringType()),
                    new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Error\Message', 'render', new StringType()),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
                        'getContentObject',
                        TypeCombinator::addNull(
                            new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer')
                        )
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
                        'getConfiguration',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Configuration\ConfigurationManager',
                        'isFeatureEnabled',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'reset',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'build',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'uriFor',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setAbsoluteUriScheme',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setAddQueryString',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setAddQueryStringMethod',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setArgumentPrefix',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setArguments',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setArgumentsToBeExcludedFromQueryString',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setCreateAbsoluteUri',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setFormat',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setLinkAccessRestrictedPages',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setNoCache',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setSection',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setTargetPageType',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setTargetPageUid',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'setUseCacheHash',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getAddQueryString',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getAddQueryStringMethod',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getArguments',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getArgumentsToBeExcludedFromQueryString',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getCreateAbsoluteUri',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getFormat',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getLinkAccessRestrictedPages',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getNoCache',
                        new BooleanType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getSection',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getTargetPageUid',
                        TypeCombinator::addNull(new IntegerType())
                    ),
                    new AddReturnTypeDeclaration(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder',
                        'getUseCacheHash',
                        new BooleanType()
                    ),
                ]),
            ],
        ]);
};
