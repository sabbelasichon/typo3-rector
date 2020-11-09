<?php

declare(strict_types=1);

use Rector\Renaming\Rector\Name\RenameClassRector;
use Ssch\TYPO3Rector\Rector\v9\v4\CallEnableFieldsFromPageRepositoryRector;
use Ssch\TYPO3Rector\Rector\v9\v4\ConstantToEnvironmentCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\DocumentTemplateAddStyleSheetRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector;
use Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitTemplateMethodCallRector;
use Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector;
use Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TYPO3\CMS\Saltedpasswords\Salt\Argon2iSalt;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/../services.php');

    $services = $containerConfigurator->services();

    $services->set(RefactorDeprecatedConcatenateMethodsPageRendererRector::class);

    $services->set(CallEnableFieldsFromPageRepositoryRector::class);

    $services->set(ConstantToEnvironmentCallRector::class);

    $services->set(RemoveInitTemplateMethodCallRector::class);

    $services->set(UseContextApiRector::class);

    $services->set(RefactorExplodeUrl2ArrayFromGeneralUtilityRector::class);

    $services->set(SystemEnvironmentBuilderConstantsRector::class);

    $services->set(RenameClassRector::class)
        ->call('configure', [[
            RenameClassRector::OLD_TO_NEW_CLASSES => [
                Argon2iSalt::class => 'TYPO3\CMS\Core\Crypto\PasswordHashing\Argon2iPasswordHash',
                'TYPO3\CMS\Saltedpasswords\Salt\BcryptSalt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\BcryptPasswordHash',
                'TYPO3\CMS\Saltedpasswords\Salt\BlowfishSalt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\BlowfishPasswordHash',
                'TYPO3\CMS\Saltedpasswords\Exception\InvalidSaltException' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\InvalidPasswordHashException',
                'TYPO3\CMS\Saltedpasswords\Salt\Md5Salt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\Md5PasswordHash',
                'TYPO3\CMS\Saltedpasswords\Salt\SaltFactory' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory',
                'TYPO3\CMS\Saltedpasswords\Salt\SaltInterface' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashInterface',
                'TYPO3\CMS\Saltedpasswords\Salt\Pbkdf2Salt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\Pbkdf2PasswordHash',
                'TYPO3\CMS\Saltedpasswords\Salt\PhpassSalt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\PhpassPasswordHash',
                'TYPO3\CMS\Saltedpasswords\Salt\AbstractComposedSalt' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\AbstractComposedSalt',
                'TYPO3\CMS\Saltedpasswords\Salt\ComposedSaltInterface' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\ComposedPasswordHashInterface',
                'TYPO3\CMS\Saltedpasswords\Utility\ExensionManagerConfigurationUtility' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\ExtensionManagerConfigurationUtility',
                'TYPO3\CMS\Saltedpasswords\SaltedPasswordService' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\SaltedPasswordService',
                'TYPO3\CMS\Saltedpasswords\Utility\SaltedPasswordsUtility' => 'TYPO3\CMS\Core\Crypto\PasswordHashing\SaltedPasswordsUtility',
            ],
        ]]);

    $services->set(UseContextApiForVersioningWorkspaceIdRector::class);
    $services->set(DocumentTemplateAddStyleSheetRector::class);
};
