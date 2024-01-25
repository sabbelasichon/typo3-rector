<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v10\v0\RemoveNoCacheHashAndUseCacheHashAttributeFluidRector;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v10\v0\ExtbasePersistenceTypoScriptRector;
use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0\EmailFinisherRector;
use Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0\TranslationFileRector;
use Ssch\TYPO3Rector\Rector\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector;
use Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector;
use Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(RemovePropertyExtensionNameRector::class);
    $rectorConfig->rule(UseNativePhpHex2binMethodRector::class);
    $rectorConfig->rule(RefactorIdnaEncodeMethodToNativeFunctionRector::class);
    $rectorConfig->rule(UseMetaDataAspectRector::class);
    $rectorConfig->rule(ForceTemplateParsingInTsfeAndTemplateServiceRector::class);
    $rectorConfig->rule(BackendUtilityGetViewDomainToPageRouterRector::class);
    $rectorConfig->rule(SetSystemLocaleFromSiteLanguageRector::class);
    $rectorConfig->rule(ConfigurationManagerAddControllerConfigurationMethodRector::class);
    $rectorConfig->rule(RemoveFormatConstantsEmailFinisherRector::class);
    $rectorConfig->rule(UseTwoLetterIsoCodeFromSiteLanguageRector::class);
    $rectorConfig->rule(UseControllerClassesInExtbasePluginsAndModulesRector::class);
    $rectorConfig->rule(ChangeDefaultCachingFrameworkNamesRector::class);
    $rectorConfig
        ->ruleWithConfiguration(ExtEmConfRector::class, [
            ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
        ]);
    $rectorConfig->rule(SwiftMailerBasedMailMessageToMailerBasedMessageRector::class);
    $rectorConfig->ruleWithConfiguration(ExtbasePersistenceTypoScriptRector::class, [
        'foo' => 'bar',
    ]);

    $rectorConfig
        ->ruleWithConfiguration(MethodCallToStaticCallRector::class, [
            new MethodCallToStaticCall(
                'TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList',
                'thumbCode',
                'TYPO3\CMS\Backend\Utility\BackendUtility',
                'thumbCode'
            ),
        ]);

    $rectorConfig
        ->ruleWithConfiguration(RenameMethodRector::class, [
            new MethodCallRename('TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList', 'requestUri', 'listURL'),
        ]);

    $rectorConfig->services()
        ->set(EmailFinisherRector::class)->tag('typo3_rector.yaml_rectors');
    $rectorConfig->services()
        ->set(TranslationFileRector::class)->tag('typo3_rector.yaml_rectors');

    $rectorConfig
        ->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
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
            new AddReturnTypeDeclaration('TYPO3\CMS\Extbase\Service\ImageService', 'getImageUri', new StringType()),
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
                new UnionType([
                    new NullType(),
                    new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer'),
                ])
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
        ]);

    $rectorConfig->services()
        ->set(RemoveNoCacheHashAndUseCacheHashAttributeFluidRector::class)->tag('typo3_rector.fluid_rectors');
};
