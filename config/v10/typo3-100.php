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
use Ssch\TYPO3Rector\Yaml\Form\Transformer\EmailFinisherTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Error\Message;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Property\TypeConverterInterface;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

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
                        DatabaseRecordList::class,
                        'thumbCode',
                        BackendUtility::class,
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
                    new MethodCallRename(DatabaseRecordList::class, 'requestUri', 'listURL'),
                ]),
            ],
        ]);

    $services->set(EmailFinisherTransformer::class);

    $services->set(AddReturnTypeDeclarationRector::class)
        ->call('configure', [
            [
                AddReturnTypeDeclarationRector::METHOD_RETURN_TYPES => ValueObjectInliner::inline([
                    new AddReturnTypeDeclaration(DomainObjectInterface::class, 'getUid', TypeCombinator::addNull(
                        new IntegerType()
                    )),
                    new AddReturnTypeDeclaration(DomainObjectInterface::class, 'getPid', TypeCombinator::addNull(
                        new IntegerType()
                    )),
                    new AddReturnTypeDeclaration(DomainObjectInterface::class, '_isNew', new BooleanType()),
                    new AddReturnTypeDeclaration(DomainObjectInterface::class, '_getProperties', new ArrayType(
                        new MixedType(),
                        new MixedType()
                    )),
                    new AddReturnTypeDeclaration(AbstractDomainObject::class, 'getUid', TypeCombinator::addNull(
                        new IntegerType()
                    )),
                    new AddReturnTypeDeclaration(AbstractDomainObject::class, 'getPid', TypeCombinator::addNull(
                        new IntegerType()
                    )),
                    new AddReturnTypeDeclaration(AbstractDomainObject::class, '_isNew', new BooleanType()),
                    new AddReturnTypeDeclaration(ImageService::class, 'applyProcessingInstructions', new ObjectType(
                        'TYPO3\CMS\Core\Resource\ProcessedFile'
                    )),
                    new AddReturnTypeDeclaration(ImageService::class, 'getImageUri', new StringType()),
                    new AddReturnTypeDeclaration(ImageService::class, 'getImage', new ObjectType(
                        'TYPO3\CMS\Core\Resource\FileInterface'
                    )),
                    new AddReturnTypeDeclaration(
                        TypeConverterInterface::class,
                        'getSupportedSourceTypes',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        TypeConverterInterface::class,
                        'getSupportedTargetType',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(
                        TypeConverterInterface::class,
                        'getTargetTypeForSource',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(TypeConverterInterface::class, 'getPriority', new IntegerType()),
                    new AddReturnTypeDeclaration(TypeConverterInterface::class, 'canConvertFrom', new BooleanType()),
                    new AddReturnTypeDeclaration(
                        TypeConverterInterface::class,
                        'getSourceChildPropertiesToBeConverted',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(
                        TypeConverterInterface::class,
                        'getTypeOfChildProperty',
                        new StringType()
                    ),
                    new AddReturnTypeDeclaration(TypeConverterInterface::class, 'convertFrom', new BooleanType()),
                    new AddReturnTypeDeclaration(Message::class, 'getMessage', new StringType()),
                    new AddReturnTypeDeclaration(Message::class, 'getCode', new IntegerType()),
                    new AddReturnTypeDeclaration(Message::class, 'getArguments', new ArrayType(
                        new MixedType(),
                        new MixedType()
                    )),
                    new AddReturnTypeDeclaration(Message::class, 'getTitle', new StringType()),
                    new AddReturnTypeDeclaration(Message::class, 'render', new StringType()),
                    new AddReturnTypeDeclaration(
                        ConfigurationManager::class,
                        'getContentObject',
                        TypeCombinator::addNull(
                            new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer')
                        )
                    ),
                    new AddReturnTypeDeclaration(ConfigurationManager::class, 'getConfiguration', new ArrayType(
                        new MixedType(),
                        new MixedType()
                    )),
                    new AddReturnTypeDeclaration(ConfigurationManager::class, 'isFeatureEnabled', new BooleanType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'reset', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'build', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'uriFor', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setAbsoluteUriScheme', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setAddQueryString', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setAddQueryStringMethod', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setArgumentPrefix', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setArguments', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(
                        UriBuilder::class,
                        'setArgumentsToBeExcludedFromQueryString',
                        new ObjectType('TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder')
                    ),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setCreateAbsoluteUri', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setFormat', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setLinkAccessRestrictedPages', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setNoCache', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setSection', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setTargetPageType', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setTargetPageUid', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'setUseCacheHash', new ObjectType(
                        'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder'
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getAddQueryString', new BooleanType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getAddQueryStringMethod', new StringType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getArguments', new ArrayType(
                        new MixedType(),
                        new MixedType()
                    )),
                    new AddReturnTypeDeclaration(
                        UriBuilder::class,
                        'getArgumentsToBeExcludedFromQueryString',
                        new ArrayType(new MixedType(), new MixedType())
                    ),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getCreateAbsoluteUri', new BooleanType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getFormat', new StringType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getLinkAccessRestrictedPages', new BooleanType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getNoCache', new BooleanType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getSection', new StringType()),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getTargetPageUid', TypeCombinator::addNull(
                        new IntegerType()
                    )),
                    new AddReturnTypeDeclaration(UriBuilder::class, 'getUseCacheHash', new BooleanType()),
                ]),
            ],
        ]);
};
