<?php

declare(strict_types=1);

use PHPStan\Type\BooleanType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\UnionType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Ssch\TYPO3Rector\TYPO314\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector;
use Ssch\TYPO3Rector\TYPO314\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateEnvironmentGetComposerRootPathRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateIpAnonymizationTaskRector;
use Ssch\TYPO3Rector\TYPO314\v0\MigrateObsoleteCharsetInSanitizeFileNameRector;
use Ssch\TYPO3Rector\TYPO314\v0\RemoveParameterInAuthenticationServiceRector;
use Ssch\TYPO3Rector\TYPO314\v0\ReplaceLocalizationParsersWitHLoaders;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');
    $rectorConfig->rule(DropFifthParameterForExtensionUtilityConfigurePluginRector::class);
    $rectorConfig->rule(ExtendExtbaseValidatorsFromAbstractValidatorRector::class);
    $rectorConfig->rule(MigrateEnvironmentGetComposerRootPathRector::class);
    $rectorConfig->rule(MigrateIpAnonymizationTaskRector::class);
    $rectorConfig->rule(MigrateObsoleteCharsetInSanitizeFileNameRector::class);
    $rectorConfig->rule(RemoveParameterInAuthenticationServiceRector::class);
    $rectorConfig->rule(ReplaceLocalizationParsersWitHLoaders::class);
    $rectorConfig->ruleWithConfiguration(
        AddReturnTypeDeclarationRector::class,
        [
            new AddReturnTypeDeclaration('TYPO3\CMS\Core\Authentication\AuthenticationService', 'processLoginData', new UnionType([new BooleanType(), new IntegerType()])),
        ]
    );
};
