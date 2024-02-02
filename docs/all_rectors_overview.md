# 101 Rules Overview

<br>

## Categories

- [CodeQuality](#codequality) (5)

- [TYPO310](#typo310) (35)

- [TYPO311](#typo311) (27)

- [TYPO312](#typo312) (34)

<br>

## CodeQuality

### ConvertImplicitVariablesToExplicitGlobalsRector

Convert `$TYPO3_CONF_VARS` to `$GLOBALS['TYPO3_CONF_VARS']`

- class: [`Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector`](../rules/CodeQuality/General/ConvertImplicitVariablesToExplicitGlobalsRector.php)

```diff
-$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
+$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
```

<br>

### ExtEmConfRector

Refactor file ext_emconf.php

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector`](../rules/CodeQuality/General/ExtEmConfRector.php)

```diff
 $EM_CONF[$_EXTKEY] = [
     'title' => 'Package Extension',
     'description' => 'Package Extension',
     'category' => 'fe',
-    'shy' => 0,
     'version' => '2.0.1',
-    'dependencies' => '',
-    'conflicts' => '',
-    'priority' => '',
-    'loadOrder' => '',
-    'module' => '',
     'state' => 'stable',
-    'uploadfolder' => 0,
-    'createDirs' => '',
-    'modify_tables' => '',
-    'clearcacheonload' => 0,
-    'lockType' => '',
     'author' => 'Max Mustermann',
     'author_email' => 'max.mustermann@mustermann.de',
     'author_company' => 'Mustermann GmbH',
-    'CGLcompliance' => '',
-    'CGLcompliance_note' => '',
     'constraints' => [
         'depends' => [
             'php' => '5.6.0-0.0.0',
             'typo3' => '7.6.0-8.99.99',
         ],
         'conflicts' => [],
         'suggests' => [],
     ],
     'autoload' =>
         [
             'psr-4' =>
                 [
                     'Foo\\Bar\\' => 'Classes/',
                 ],
         ],
     '_md5_values_when_last_written' => 'a:0:{}',
 ];
```

<br>

### InjectMethodToConstructorInjectionRector



- class: [`Ssch\TYPO3Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector`](../rules/CodeQuality/General/InjectMethodToConstructorInjectionRector.php)

```diff
 namespace App\Service;

 use \TYPO3\CMS\Core\Cache\CacheManager;

 class Service
 {
     private CacheManager $cacheManager;

-    public function injectCacheManager(CacheManager $cacheManager): void
+    public function __construct(CacheManager $cacheManager)
     {
         $this->cacheManager = $cacheManager;
     }
 }
```

<br>

### MethodGetInstanceToMakeInstanceCallRector

Use GeneralUtility::makeInstance instead of getInstance call

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector`](../rules/CodeQuality/General/MethodGetInstanceToMakeInstanceCallRector.php)

```diff
-$instance = TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
+use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
+
+$instance = GeneralUtility::makeInstance(ExtractorRegistry::class);
```

<br>

### RenameClassMapAliasRector

Replaces defined classes by new ones.

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\CodeQuality\General\RenameClassMapAliasRector`](../rules/CodeQuality/General/RenameClassMapAliasRector.php)

```diff
 namespace App;

-use t3lib_div;
+use TYPO3\CMS\Core\Utility\GeneralUtility;

 function someFunction()
 {
-    t3lib_div::makeInstance(\tx_cms_BackendLayout::class);
+    GeneralUtility::makeInstance(\TYPO3\CMS\Backend\View\BackendLayoutView::class);
 }
```

<br>

## TYPO310

### BackendUtilityEditOnClickRector

Migrate the method `BackendUtility::editOnClick()` to use UriBuilder API

- class: [`Ssch\TYPO3Rector\TYPO310\v1\BackendUtilityEditOnClickRector`](../rules/TYPO310/v1/BackendUtilityEditOnClickRector.php)

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br>

### BackendUtilityGetViewDomainToPageRouterRector

Refactor method call `BackendUtility::getViewDomain()` to PageRouter

- class: [`Ssch\TYPO3Rector\TYPO310\v0\BackendUtilityGetViewDomainToPageRouterRector`](../rules/TYPO310/v0/BackendUtilityGetViewDomainToPageRouterRector.php)

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
+use TYPO3\CMS\Core\Site\SiteFinder;
+use TYPO3\CMS\Core\Utility\GeneralUtility;

-$domain1 = BackendUtility::getViewDomain(1);
+$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1);
+$domain1 = $site->getRouter()->generateUri(1);
```

<br>

### ChangeDefaultCachingFrameworkNamesRector

Use new default cache names like core instead of cache_core)

- class: [`Ssch\TYPO3Rector\TYPO310\v0\ChangeDefaultCachingFrameworkNamesRector`](../rules/TYPO310/v0/ChangeDefaultCachingFrameworkNamesRector.php)

```diff
 $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
-$cacheManager->getCache('cache_core');
-$cacheManager->getCache('cache_hash');
-$cacheManager->getCache('cache_pages');
-$cacheManager->getCache('cache_pagesection');
-$cacheManager->getCache('cache_runtime');
-$cacheManager->getCache('cache_rootline');
-$cacheManager->getCache('cache_imagesizes');
+$cacheManager->getCache('core');
+$cacheManager->getCache('hash');
+$cacheManager->getCache('pages');
+$cacheManager->getCache('pagesection');
+$cacheManager->getCache('runtime');
+$cacheManager->getCache('rootline');
+$cacheManager->getCache('imagesizes');
```

<br>

### ConfigurationManagerAddControllerConfigurationMethodRector

Add additional method getControllerConfiguration for AbstractConfigurationManager

- class: [`Ssch\TYPO3Rector\TYPO310\v0\ConfigurationManagerAddControllerConfigurationMethodRector`](../rules/TYPO310/v0/ConfigurationManagerAddControllerConfigurationMethodRector.php)

```diff
 final class MyExtbaseConfigurationManager extends AbstractConfigurationManager
 {
     protected function getSwitchableControllerActions($extensionName, $pluginName)
     {
         $switchableControllerActions = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['modules'][$pluginName]['controllers'] ?? false;
         if ( ! is_array($switchableControllerActions)) {
             $switchableControllerActions = [];
         }

         return $switchableControllerActions;
     }
+
+    protected function getControllerConfiguration($extensionName, $pluginName): array
+    {
+        return $this->getSwitchableControllerActions($extensionName, $pluginName);
+    }
 }
```

<br>

### ExcludeServiceKeysToArrayRector

Change parameter `$excludeServiceKeys` explicity to an array

- class: [`Ssch\TYPO3Rector\TYPO310\v2\ExcludeServiceKeysToArrayRector`](../rules/TYPO310/v2/ExcludeServiceKeysToArrayRector.php)

```diff
-GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
-ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
+GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
+ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
```

<br>

### ForceTemplateParsingInTsfeAndTemplateServiceRector

Force template parsing in tsfe is replaced with context api and aspects

- class: [`Ssch\TYPO3Rector\TYPO310\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector`](../rules/TYPO310/v0/ForceTemplateParsingInTsfeAndTemplateServiceRector.php)

```diff
-$myvariable = $GLOBALS['TSFE']->forceTemplateParsing;
-$myvariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;
+$myvariable = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
+$myvariable2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

-$GLOBALS['TSFE']->forceTemplateParsing = true;
-$GLOBALS['TSFE']->tmpl->forceTemplateParsing = true;
+\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));
+\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->setAspect('typoscript', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\TypoScriptAspect::class, true));
```

<br>

### InjectEnvironmentServiceIfNeededInResponseRector

Inject EnvironmentService if needed in subclass of Response

- class: [`Ssch\TYPO3Rector\TYPO310\v2\InjectEnvironmentServiceIfNeededInResponseRector`](../rules/TYPO310/v2/InjectEnvironmentServiceIfNeededInResponseRector.php)

```diff
 class MyResponse extends Response
 {
+    /**
+     * @var \TYPO3\CMS\Extbase\Service\EnvironmentService
+     */
+    protected $environmentService;
+
     public function myMethod()
     {
         if ($this->environmentService->isEnvironmentInCliMode()) {

         }
+    }
+
+    public function injectEnvironmentService(\TYPO3\CMS\Extbase\Service\EnvironmentService $environmentService)
+    {
+        $this->environmentService = $environmentService;
     }
 }

 class MyOtherResponse extends Response
 {
     public function myMethod()
     {

     }
 }
```

<br>

### MoveApplicationContextToEnvironmentApiRector

Use Environment API to fetch application context

- class: [`Ssch\TYPO3Rector\TYPO310\v2\MoveApplicationContextToEnvironmentApiRector`](../rules/TYPO310/v2/MoveApplicationContextToEnvironmentApiRector.php)

```diff
-GeneralUtility::getApplicationContext();
+Environment::getContext();
```

<br>

### RefactorCHashArrayOfTSFERector

Refactor Internal public property cHash_array

- class: [`Ssch\TYPO3Rector\TYPO310\v1\RefactorCHashArrayOfTSFERector`](../rules/TYPO310/v1/RefactorCHashArrayOfTSFERector.php)

```diff
-$cHash_array = $GLOBALS['TSFE']->cHash_array;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\HttpUtility;
+use TYPO3\CMS\Frontend\Page\CacheHashCalculator;
+
+$relevantParametersForCachingFromPageArguments = [];
+$pageArguments = $GLOBALS['REQUEST']->getAttribute('routing');
+$queryParams = $pageArguments->getDynamicArguments();
+if (!empty($queryParams) && ($pageArguments->getArguments()['cHash'] ?? false)) {
+    $queryParams['id'] = $pageArguments->getPageId();
+    $relevantParametersForCachingFromPageArguments = GeneralUtility::makeInstance(CacheHashCalculator::class)->getRelevantParameters(HttpUtility::buildQueryString($queryParams));
+}
+$cHash_array = $relevantParametersForCachingFromPageArguments;
```

<br>

### RefactorIdnaEncodeMethodToNativeFunctionRector

Use native function idn_to_ascii instead of GeneralUtility::idnaEncode

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RefactorIdnaEncodeMethodToNativeFunctionRector`](../rules/TYPO310/v0/RefactorIdnaEncodeMethodToNativeFunctionRector.php)

```diff
-$domain = GeneralUtility::idnaEncode('domain.com');
-$email = GeneralUtility::idnaEncode('email@domain.com');
+$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
+$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
```

<br>

### RefactorInternalPropertiesOfTSFERector

Refactor Internal public TSFE properties

- class: [`Ssch\TYPO3Rector\TYPO310\v1\RefactorInternalPropertiesOfTSFERector`](../rules/TYPO310/v1/RefactorInternalPropertiesOfTSFERector.php)

```diff
-$domainStartPage = $GLOBALS['TSFE']->domainStartPage;
+$cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];
```

<br>

### RegisterPluginWithVendorNameRector

Remove vendor name from registerPlugin call

- class: [`Ssch\TYPO3Rector\TYPO310\v1\RegisterPluginWithVendorNameRector`](../rules/TYPO310/v1/RegisterPluginWithVendorNameRector.php)

```diff
 TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
-   'TYPO3.CMS.Form',
+   'Form',
    'Formframework',
    'Form',
    'content-form',
 );
```

<br>

### RemoveEnableMultiSelectFilterTextfieldRector

Remove "enableMultiSelectFilterTextfield" => true as its default

- class: [`Ssch\TYPO3Rector\TYPO310\v1\RemoveEnableMultiSelectFilterTextfieldRector`](../rules/TYPO310/v1/RemoveEnableMultiSelectFilterTextfieldRector.php)

```diff
 return [
     'columns' => [
         'foo' => [
             'label' => 'foo',
             'config' => [
                 'type' => 'select',
                 'renderType' => 'selectMultipleSideBySide',
-                'enableMultiSelectFilterTextfield' => true,
             ],
         ],
     ],
 ];
```

<br>

### RemoveExcludeOnTransOrigPointerFieldRector

transOrigPointerField is not longer allowed to be excluded

- class: [`Ssch\TYPO3Rector\TYPO310\v3\RemoveExcludeOnTransOrigPointerFieldRector`](../rules/TYPO310/v3/RemoveExcludeOnTransOrigPointerFieldRector.php)

```diff
 return [
     'ctrl' => [
         'transOrigPointerField' => 'l10n_parent',
     ],
     'columns' => [
         'l10n_parent' => [
-            'exclude' => true,
             'config' => [
                 'type' => 'select',
             ],
         ],
     ],
 ];
```

<br>

### RemoveFormatConstantsEmailFinisherRector

Remove constants FORMAT_PLAINTEXT and FORMAT_HTML of class `TYPO3\CMS\Form\Domain\Finishers\EmailFinisher`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemoveFormatConstantsEmailFinisherRector`](../rules/TYPO310/v0/RemoveFormatConstantsEmailFinisherRector.php)

```diff
-$this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
+$this->setOption('addHtmlPart', true);
```

<br>

### RemovePropertyExtensionNameRector

Use method getControllerExtensionName from `$request` property instead of removed property `$extensionName`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemovePropertyExtensionNameRector`](../rules/TYPO310/v0/RemovePropertyExtensionNameRector.php)

```diff
 class MyCommandController extends CommandController
 {
     public function myMethod()
     {
-        if ($this->extensionName === 'whatever') {
+        if ($this->request->getControllerExtensionName() === 'whatever') {

         }

-        $extensionName = $this->extensionName;
+        $extensionName = $this->request->getControllerExtensionName();
     }
 }
```

<br>

### RemoveSeliconFieldPathRector

TCA option "selicon_field_path" removed

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemoveSeliconFieldPathRector`](../rules/TYPO310/v0/RemoveSeliconFieldPathRector.php)

```diff
 return [
     'ctrl' => [
         'selicon_field' => 'icon',
-        'selicon_field_path' => 'uploads/media'
     ],
 ];
```

<br>

### RemoveShowRecordFieldListInsideInterfaceSectionRector

Remove showRecordFieldList inside section interface

- class: [`Ssch\TYPO3Rector\TYPO310\v3\RemoveShowRecordFieldListInsideInterfaceSectionRector`](../rules/TYPO310/v3/RemoveShowRecordFieldListInsideInterfaceSectionRector.php)

```diff
 return [
     'ctrl' => [
     ],
-    'interface' => [
-        'showRecordFieldList' => 'foo,bar,baz',
-    ],
     'columns' => [
     ],
 ];
```

<br>

### RemoveTcaOptionSetToDefaultOnCopyRector

TCA option setToDefaultOnCopy removed

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemoveTcaOptionSetToDefaultOnCopyRector`](../rules/TYPO310/v0/RemoveTcaOptionSetToDefaultOnCopyRector.php)

```diff
 return [
     'ctrl' => [
-        'selicon_field' => 'icon',
-        'setToDefaultOnCopy' => 'foo'
+        'selicon_field' => 'icon'
     ],
     'columns' => [
     ],
 ];
```

<br>

### SendNotifyEmailToMailApiRector

Refactor ContentObjectRenderer::sendNotifyEmail to MailMessage-API

- class: [`Ssch\TYPO3Rector\TYPO310\v1\SendNotifyEmailToMailApiRector`](../rules/TYPO310/v1/SendNotifyEmailToMailApiRector.php)

```diff
-$GLOBALS['TSFE']->cObj->sendNotifyEmail("Subject\nMessage", 'max.mustermann@domain.com', 'max.mustermann@domain.com', 'max.mustermann@domain.com');
+use Symfony\Component\Mime\Address;
+use TYPO3\CMS\Core\Mail\MailMessage;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\MailUtility;$success = false;
+
+$mail = GeneralUtility::makeInstance(MailMessage::class);
+$message = trim("Subject\nMessage");
+$senderName = trim(null);
+$senderAddress = trim('max.mustermann@domain.com');
+
+if ($senderAddress !== '') {
+    $mail->from(new Address($senderAddress, $senderName));
+}
+
+if ($message !== '') {
+    $messageParts = explode(LF, $message, 2);
+    $subject = trim($messageParts[0]);
+    $plainMessage = trim($messageParts[1]);
+    $parsedRecipients = MailUtility::parseAddresses('max.mustermann@domain.com');
+    if (!empty($parsedRecipients)) {
+        $mail->to(...$parsedRecipients)->subject($subject)->text($plainMessage);
+        $mail->send();
+    }
+    $success = true;
+}
```

<br>

### SetSystemLocaleFromSiteLanguageRector

Refactor `TypoScriptFrontendController->settingLocale()` to `Locales::setSystemLocaleFromSiteLanguage()`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\SetSystemLocaleFromSiteLanguageRector`](../rules/TYPO310/v0/SetSystemLocaleFromSiteLanguageRector.php)

```diff
+use TYPO3\CMS\Core\Localization\Locales;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

 $controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
-$controller->settingLocale();
+Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
```

<br>

### SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector

Substitute deprecated method calls of class GeneralUtility

- class: [`Ssch\TYPO3Rector\TYPO310\v4\SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector`](../rules/TYPO310/v4/SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $hex = '127.0.0.1';
-GeneralUtility::IPv6Hex2Bin($hex);
+inet_pton($hex);
 $bin = $packed = chr(127) . chr(0) . chr(0) . chr(1);
-GeneralUtility::IPv6Bin2Hex($bin);
+inet_ntop($bin);
 $address = '127.0.0.1';
-GeneralUtility::compressIPv6($address);
-GeneralUtility::milliseconds();
+inet_ntop(inet_pton($address));
+round(microtime(true) * 1000);
```

<br>

### SubstituteResourceFactoryRector

Substitue `ResourceFactory::getInstance()` through GeneralUtility::makeInstance(ResourceFactory::class)

- class: [`Ssch\TYPO3Rector\TYPO310\v3\SubstituteResourceFactoryRector`](../rules/TYPO310/v3/SubstituteResourceFactoryRector.php)

```diff
-$resourceFactory = ResourceFactory::getInstance();
+$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
```

<br>

### SwiftMailerBasedMailMessageToMailerBasedMessageRector

New Mail API based on symfony/mailer and symfony/mime

- class: [`Ssch\TYPO3Rector\TYPO310\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector`](../rules/TYPO310/v0/SwiftMailerBasedMailMessageToMailerBasedMessageRector.php)

```diff
-use Swift_Attachment;
 use TYPO3\CMS\Core\Mail\MailMessage;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $mail = GeneralUtility::makeInstance(MailMessage::class);

 $mail
     ->setSubject('Your subject')
     ->setFrom(['john@doe.com' => 'John Doe'])
     ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
-    ->setBody('Here is the message itself')
-    ->addPart('<p>Here is the message itself</p>', 'text/html')
-    ->attach(Swift_Attachment::fromPath('my-document.pdf'))
+    ->text('Here is the message itself')
+    ->html('<p>Here is the message itself</p>')
+    ->attachFromPath('my-document.pdf')
     ->send();
```

<br>

### UnifiedFileNameValidatorRector

GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)

- class: [`Ssch\TYPO3Rector\TYPO310\v4\UnifiedFileNameValidatorRector`](../rules/TYPO310/v4/UnifiedFileNameValidatorRector.php)

```diff
+use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $filename = 'somefile.php';
-if (!GeneralUtility::verifyFilenameAgainstDenyPattern($filename)) {
+if (!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)) {
 }

-if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FILE_DENY_PATTERN_DEFAULT)
+if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FileNameValidator::DEFAULT_FILE_DENY_PATTERN)
 {
 }
```

<br>

### UseActionControllerRector

Use ActionController class instead of AbstractController if used

- class: [`Ssch\TYPO3Rector\TYPO310\v2\UseActionControllerRector`](../rules/TYPO310/v2/UseActionControllerRector.php)

```diff
-class MyController extends AbstractController
+use Symfony\Component\HttpFoundation\Response;
+use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
+
+class MyController extends ActionController
 {
 }
```

<br>

### UseClassTypo3InformationRector

Use class Typo3Information

- class: [`Ssch\TYPO3Rector\TYPO310\v3\UseClassTypo3InformationRector`](../rules/TYPO310/v3/UseClassTypo3InformationRector.php)

```diff
-$urlGeneral = TYPO3_URL_GENERAL;
-$urlLicense = TYPO3_URL_LICENSE;
-$urlException = TYPO3_URL_EXCEPTION;
-$urlDonate = TYPO3_URL_DONATE;
-$urlOpcache = TYPO3_URL_WIKI_OPCODECACHE;
+use TYPO3\CMS\Core\Information\Typo3Information;
+$urlGeneral = Typo3Information::TYPO3_URL_GENERAL;
+$urlLicense = Typo3Information::TYPO3_URL_LICENSE;
+$urlException = Typo3Information::TYPO3_URL_EXCEPTION;
+$urlDonate = Typo3Information::TYPO3_URL_DONATE;
+$urlOpcache = Typo3Information::TYPO3_URL_WIKI_OPCODECACHE;
```

<br>

### UseClassTypo3VersionRector

Use class Typo3Version instead of the constants

- class: [`Ssch\TYPO3Rector\TYPO310\v3\UseClassTypo3VersionRector`](../rules/TYPO310/v3/UseClassTypo3VersionRector.php)

```diff
-$typo3Version = TYPO3_version;
-$typo3Branch = TYPO3_branch;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Information\Typo3Version;
+$typo3Version = GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
+$typo3Branch = GeneralUtility::makeInstance(Typo3Version::class)->getBranch();
```

<br>

### UseControllerClassesInExtbasePluginsAndModulesRector

Use controller classes when registering extbase plugins/modules

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseControllerClassesInExtbasePluginsAndModulesRector`](../rules/TYPO310/v0/UseControllerClassesInExtbasePluginsAndModulesRector.php)

```diff
-use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
-ExtensionUtility::configurePlugin(
-    'TYPO3.CMS.Form',
+use TYPO3\CMS\Extbase\Utility\ExtensionUtility;ExtensionUtility::configurePlugin(
+    'Form',
     'Formframework',
-    ['FormFrontend' => 'render, perform'],
-    ['FormFrontend' => 'perform'],
+    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'render, perform'],
+    [\TYPO3\CMS\Form\Controller\FormFrontendController::class => 'perform'],
     ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
 );
```

<br>

### UseFileGetContentsForGetUrlRector

Rewirte Method Calls of GeneralUtility::getUrl("somefile.csv") to `@file_get_contents`

- class: [`Ssch\TYPO3Rector\TYPO310\v4\UseFileGetContentsForGetUrlRector`](../rules/TYPO310/v4/UseFileGetContentsForGetUrlRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Http\RequestFactory;

-GeneralUtility::getUrl('some.csv');
+@file_get_contents('some.csv');
 $externalUrl = 'https://domain.com';
-GeneralUtility::getUrl($externalUrl);
+GeneralUtility::makeInstance(RequestFactory::class)->request($externalUrl)->getBody()->getContents();
```

<br>

### UseIconsFromSubFolderInIconRegistryRector

Use icons from subfolder in IconRegistry

- class: [`Ssch\TYPO3Rector\TYPO310\v4\UseIconsFromSubFolderInIconRegistryRector`](../rules/TYPO310/v4/UseIconsFromSubFolderInIconRegistryRector.php)

```diff
 \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
         ->registerIcon(
             'apps-pagetree-reference',
             TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
             [
-                'source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/content/content-text.svg',
+                'source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/svgs/content/content-text.svg',
             ]
         );
```

<br>

### UseMetaDataAspectRector

Use `$fileObject->getMetaData()->get()` instead of `$fileObject->_getMetaData()`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseMetaDataAspectRector`](../rules/TYPO310/v0/UseMetaDataAspectRector.php)

```diff
 $fileObject = new File();
-$fileObject->_getMetaData();
+$fileObject->getMetaData()->get();
```

<br>

### UseNativePhpHex2binMethodRector

Turns TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php hex2bin

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseNativePhpHex2binMethodRector`](../rules/TYPO310/v0/UseNativePhpHex2binMethodRector.php)

```diff
-TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br>

### UseTwoLetterIsoCodeFromSiteLanguageRector

The usage of the propery sys_language_isocode is deprecated. Use method getTwoLetterIsoCode of SiteLanguage

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseTwoLetterIsoCodeFromSiteLanguageRector`](../rules/TYPO310/v0/UseTwoLetterIsoCodeFromSiteLanguageRector.php)

```diff
-if ($GLOBALS['TSFE']->sys_language_isocode) {
-    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
+if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
+    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
 }
```

<br>

### UseTypo3InformationForCopyRightNoticeRector

Migrate the method `BackendUtility::TYPO3_copyRightNotice()` to use Typo3Information API

- class: [`Ssch\TYPO3Rector\TYPO310\v2\UseTypo3InformationForCopyRightNoticeRector`](../rules/TYPO310/v2/UseTypo3InformationForCopyRightNoticeRector.php)

```diff
-$copyright = BackendUtility::TYPO3_copyRightNotice();
+$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
```

<br>

## TYPO311

### AddSetConfigurationMethodToExceptionHandlerRector

Add method setConfiguration to class which implements ExceptionHandlerInterface

- class: [`Ssch\TYPO3Rector\TYPO311\v4\AddSetConfigurationMethodToExceptionHandlerRector`](../rules/TYPO311/v4/AddSetConfigurationMethodToExceptionHandlerRector.php)

```diff
 use TYPO3\CMS\Frontend\ContentObject\Exception\ExceptionHandlerInterface;
 use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

 class CustomExceptionHandler implements ExceptionHandlerInterface
 {
     private array $configuration;

-    public function __construct(array $configuration) {
-        $this->configuration = $configuration;
+    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
+    {
     }

-    public function handle(\Exception $exception, AbstractContentObject $contentObject = null, $contentObjectConfiguration = [])
+    public function setConfiguration(array $configuration): void
     {
+        $this->configuration = $configuration;
     }
 }
```

<br>

### DateTimeAspectInsteadOfGlobalsExecTimeRector

Use DateTimeAspect instead of superglobals like `$GLOBALS['EXEC_TIME']`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector`](../rules/TYPO311/v0/DateTimeAspectInsteadOfGlobalsExecTimeRector.php)

```diff
-$currentTimestamp = $GLOBALS['EXEC_TIME'];
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+
+$currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
```

<br>

### ExtbaseControllerActionsMustReturnResponseInterfaceRector

Extbase controller actions must return ResponseInterface

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\TYPO311\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector`](../rules/TYPO311/v0/ExtbaseControllerActionsMustReturnResponseInterfaceRector.php)

```diff
+use Psr\Http\Message\ResponseInterface;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyController extends ActionController
 {
-    public function someAction()
+    public function someAction(): ResponseInterface
     {
         $this->view->assign('foo', 'bar');
+        return $this->htmlResponse();
     }
 }
```

<br>

### FlexFormToolsArrayValueByPathRector

Replace deprecated FlexFormTools methods with ArrayUtility methods

- class: [`Ssch\TYPO3Rector\TYPO311\v5\FlexFormToolsArrayValueByPathRector`](../rules/TYPO311/v5/FlexFormToolsArrayValueByPathRector.php)

```diff
-use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
+use TYPO3\CMS\Core\Utility\ArrayUtility;

-$flexFormTools = new FlexFormTools();
 $searchArray = [];
-$value = $flexFormTools->getArrayValueByPath('search/path', $searchArray);
+$value = ArrayUtility::getValueByPath($searchArray, 'search/path');

-$flexFormTools->setArrayValueByPath('set/path', $dataArray, $value);
+$dataArray = ArrayUtility::setValueByPath($dataArray, 'set/path', $value);
```

<br>

### ForwardResponseInsteadOfForwardMethodRector

Return `TYPO3\CMS\Extbase\Http\ForwardResponse` instead of `TYPO3\CMS\Extbase\Mvc\Controller\ActionController::forward()`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\ForwardResponseInsteadOfForwardMethodRector`](../rules/TYPO311/v0/ForwardResponseInsteadOfForwardMethodRector.php)

```diff
+use Psr\Http\Message\ResponseInterface;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
+use TYPO3\CMS\Extbase\Http\ForwardResponse;

 class FooController extends ActionController
 {
-   public function listAction()
+   public function listAction(): ResponseInterface
    {
-        $this->forward('show');
+        return new ForwardResponse('show');
    }
 }
```

<br>

### GetClickMenuOnIconTagParametersRector

Use `BackendUtility::getClickMenuOnIconTagParameters()` instead `BackendUtility::wrapClickMenuOnIcon()` if needed

- class: [`Ssch\TYPO3Rector\TYPO311\v0\GetClickMenuOnIconTagParametersRector`](../rules/TYPO311/v0/GetClickMenuOnIconTagParametersRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
 $returnTagParameters = true;
-BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
+BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
```

<br>

### HandleCObjRendererATagParamsMethodRector

Removes deprecated params of the `ContentObjectRenderer->getATagParams()` method

- class: [`Ssch\TYPO3Rector\TYPO311\v5\HandleCObjRendererATagParamsMethodRector`](../rules/TYPO311/v5/HandleCObjRendererATagParamsMethodRector.php)

```diff
 $cObjRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
-$bar = $cObjRenderer->getATagParams([], false);
+$bar = $cObjRenderer->getATagParams([]);
```

<br>

### MigrateFileFolderConfigurationRector

Migrate file folder config

- class: [`Ssch\TYPO3Rector\TYPO311\v4\MigrateFileFolderConfigurationRector`](../rules/TYPO311/v4/MigrateFileFolderConfigurationRector.php)

```diff
 'aField' => [
    'config' => [
       'type' => 'select',
       'renderType' => 'selectSingle',
-      'fileFolder' => 'EXT:my_ext/Resources/Public/Icons',
-      'fileFolder_extList' => 'svg',
-      'fileFolder_recursions' => 1,
+      'fileFolderConfig' => [
+         'folder' => 'EXT:styleguide/Resources/Public/Icons',
+         'allowedExtensions' => 'svg',
+         'depth' => 1,
+      ]
    ]
 ]
```

<br>

### MigrateFrameModuleToSvgTreeRector

Migrate the iframe based file tree to SVG

- class: [`Ssch\TYPO3Rector\TYPO311\v2\MigrateFrameModuleToSvgTreeRector`](../rules/TYPO311/v2/MigrateFrameModuleToSvgTreeRector.php)

```diff
-'navigationFrameModule' => 'file_navframe'
+'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer'
```

<br>

### MigrateLanguageFieldToTcaTypeLanguageRector

use the new TCA type language instead of foreign_table => sys_language for selecting a records

- class: [`Ssch\TYPO3Rector\TYPO311\v3\MigrateLanguageFieldToTcaTypeLanguageRector`](../rules/TYPO311/v3/MigrateLanguageFieldToTcaTypeLanguageRector.php)

```diff
 return [
     'ctrl' => [
         'languageField' => 'sys_language_uid',
     ],
     'columns' => [
         'sys_language_uid' => [
             'exclude' => 1,
             'label' => 'Language',
             'config' => [
-                'type' => 'select',
-                'renderType' => 'selectSingle',
-                'foreign_table' => 'sys_language',
-                'foreign_table_where' => 'ORDER BY sys_language.title',
-                'eval' => 'int',
-                'items' => [
-                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
-                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
-                ],
+                'type' => 'language',
             ],
         ],
     ],
 ];
```

<br>

### MigrateSpecialLanguagesToTcaTypeLanguageRector

use the new TCA type language instead of foreign_table => sys_language for selecting a records

- class: [`Ssch\TYPO3Rector\TYPO311\v3\MigrateSpecialLanguagesToTcaTypeLanguageRector`](../rules/TYPO311/v3/MigrateSpecialLanguagesToTcaTypeLanguageRector.php)

```diff
 return [
     'ctrl' => [
         'languageField' => 'sys_language_uid',
     ],
     'columns' => [
         'sys_language_uid' => [
             'exclude' => true,
             'label' => 'Language',
             'config' => [
-                'type' => 'select',
-                'renderType' => 'selectSingle',
-                'special' => 'languages',
-                'items' => [
-                    [
-                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
-                        -1,
-                        'flags-multiple'
-                    ],
-                ],
-                'default' => 0,
+                'type' => 'language',
             ],
         ],
     ],
 ];
```

<br>

### ProvideCObjViaMethodRector

Replaces public `$cObj` with protected and set via method

- class: [`Ssch\TYPO3Rector\TYPO311\v4\ProvideCObjViaMethodRector`](../rules/TYPO311/v4/ProvideCObjViaMethodRector.php)

```diff
 class Foo
 {
-    public $cObj;
+    protected $cObj;
+
+    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
+    {
+        $this->cObj = $cObj;
+    }
 }
```

<br>

### RemoveDefaultInternalTypeDBRector

Remove the default type for internal_type

- class: [`Ssch\TYPO3Rector\TYPO311\v5\RemoveDefaultInternalTypeDBRector`](../rules/TYPO311/v5/RemoveDefaultInternalTypeDBRector.php)

```diff
 return [
     'columns' => [
         'foobar' => [
             'config' => [
                 'type' => 'group',
-                'internal_type' => 'db',
             ],
         ],
     ],
 ];
```

<br>

### RemoveWorkspacePlaceholderShadowColumnsConfigurationRector

removeWorkspacePlaceholderShadowColumnsConfiguration

- class: [`Ssch\TYPO3Rector\TYPO311\v0\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector`](../rules/TYPO311/v0/RemoveWorkspacePlaceholderShadowColumnsConfigurationRector.php)

```diff
 return [
     'ctrl' => [
-        'shadowColumnsForNewPlaceholders' => '',
-        'shadowColumnsForMovePlaceholders' => '',
     ],
 ];
```

<br>

### ReplaceInjectAnnotationWithMethodRector

Turns properties with `@TYPO3\CMS\Extbase\Annotation\Inject` to setter injection

- class: [`Ssch\TYPO3Rector\TYPO311\v0\ReplaceInjectAnnotationWithMethodRector`](../rules/TYPO311/v0/ReplaceInjectAnnotationWithMethodRector.php)

```diff
 /**
  * @var SomeService
- * @TYPO3\CMS\Extbase\Annotation\Inject
  */
-private $someService;
+private $someService;
+
+public function injectSomeService(SomeService $someService)
+{
+    $this->someService = $someService;
+}
```

<br>

### ReplaceTSFEATagParamsCallOnGlobalsRector

Replaces all direct calls to `$GLOBALS['TSFE']->ATagParams.`

- class: [`Ssch\TYPO3Rector\TYPO311\v5\ReplaceTSFEATagParamsCallOnGlobalsRector`](../rules/TYPO311/v5/ReplaceTSFEATagParamsCallOnGlobalsRector.php)

```diff
-$foo = $GLOBALS['TSFE']->ATagParams;
+$foo = $GLOBALS['TSFE']->config['config']['ATagParams'] ?? '';
```

<br>

### SimplifyCheckboxItemsTCARector

Simplify checkbox items TCA

- class: [`Ssch\TYPO3Rector\TYPO311\v5\SimplifyCheckboxItemsTCARector`](../rules/TYPO311/v5/SimplifyCheckboxItemsTCARector.php)

```diff
 return [
     'columns' => [
         'enabled' => [
             'label' => 'enabled',
             'config' => [
                 'type' => 'check',
                 'renderType' => 'checkboxToggle',
                 'default' => 1,
-                'items' => [
-                    [
-                        0 => '',
-                        1 => '',
-                    ],
-                ],
             ],
         ],
         'hidden' => [
             'label' => 'hidden',
             'config' => [
                 'type' => 'check',
                 'renderType' => 'checkboxToggle',
                 'default' => 0,
                 'items' => [
                     [
                         0 => '',
-                        1 => '',
                         'invertStateDisplay' => true,
                     ],
                 ],
             ],
         ],
     ],
 ];
```

<br>

### SubstituteBackendTemplateViewWithModuleTemplateRector

Use an instance of ModuleTemplate instead of BackendTemplateView

- class: [`Ssch\TYPO3Rector\TYPO311\v5\SubstituteBackendTemplateViewWithModuleTemplateRector`](../rules/TYPO311/v5/SubstituteBackendTemplateViewWithModuleTemplateRector.php)

```diff
 class MyController extends ActionController
 {
-    protected $defaultViewObjectName = BackendTemplateView::class;
+    protected ModuleTemplateFactory $moduleTemplateFactory;

+    public function __construct(
+        ModuleTemplateFactory $moduleTemplateFactory,
+    ) {
+        $this->moduleTemplateFactory = $moduleTemplateFactory;
+    }
+
     public function myAction(): ResponseInterface
     {
         $this->view->assign('someVar', 'someContent');
-        $moduleTemplate = $this->view->getModuleTemplate();
+        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
         // Adding title, menus, buttons, etc. using $moduleTemplate ...
-        return $this->htmlResponse();
+        $moduleTemplate->setContent($this->view->render());
+        return $this->htmlResponse($moduleTemplate->renderContent());
     }
 }
```

<br>

### SubstituteConstantsModeAndRequestTypeRector

Substitute TYPO3_MODE and TYPO3_REQUESTTYPE constants

- class: [`Ssch\TYPO3Rector\TYPO311\v0\SubstituteConstantsModeAndRequestTypeRector`](../rules/TYPO311/v0/SubstituteConstantsModeAndRequestTypeRector.php)

```diff
-defined('TYPO3_MODE') or die();
+defined('TYPO3') or die();
```

<br>

### SubstituteEnvironmentServiceWithApplicationTypeRector

Substitute class EnvironmentService with ApplicationType class\"

- class: [`Ssch\TYPO3Rector\TYPO311\v2\SubstituteEnvironmentServiceWithApplicationTypeRector`](../rules/TYPO311/v2/SubstituteEnvironmentServiceWithApplicationTypeRector.php)

```diff
-if($this->environmentService->isEnvironmentInFrontendMode()) {
+if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend())
     ...
 }
```

<br>

### SubstituteExtbaseRequestGetBaseUriRector

Use PSR-7 compatible request for uri instead of the method getBaseUri

- class: [`Ssch\TYPO3Rector\TYPO311\v3\SubstituteExtbaseRequestGetBaseUriRector`](../rules/TYPO311/v3/SubstituteExtbaseRequestGetBaseUriRector.php)

```diff
-$baseUri = $this->request->getBaseUri();
+$request = $GLOBALS['TYPO3_REQUEST'];
+/** @var NormalizedParams $normalizedParams */
+$normalizedParams = $request->getAttribute('normalizedParams');
+$baseUri = $normalizedParams->getSiteUrl();
```

<br>

### SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector

Use PageRenderer and IconFactory directly instead of getting them from the ModuleTemplate

- class: [`Ssch\TYPO3Rector\TYPO311\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector`](../rules/TYPO311/v5/SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector.php)

```diff
 class MyController extends ActionController
 {
     protected ModuleTemplateFactory $moduleTemplateFactory;
+    protected IconFactory $iconFactory;
+    protected PageRenderer $pageRenderer;

-    public function __construct(ModuleTemplateFactory $moduleTemplateFactory)
-    {
+    public function __construct(
+        ModuleTemplateFactory $moduleTemplateFactory,
+        IconFactory $iconFactory,
+        PageRenderer $pageRenderer
+    ) {
         $this->moduleTemplateFactory = $moduleTemplateFactory;
+        $this->iconFactory = $iconFactory;
+        $this->pageRenderer = $pageRenderer;
     }

     public function myAction(): ResponseInterface
     {
         $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
-        $moduleTemplate->getPageRenderer()->loadRequireJsModule('Vendor/Extension/MyJsModule');
-        $moduleTemplate->setContent($moduleTemplate->getIconFactory()->getIcon('some-icon', Icon::SIZE_SMALL)->render());
+        $this->pageRenderer->loadRequireJsModule('Vendor/Extension/MyJsModule');
+        $moduleTemplate->setContent($this->iconFactory->getIcon('some-icon', Icon::SIZE_SMALL)->render());
         return $this->htmlResponse($moduleTemplate->renderContent());
     }
 }
```

<br>

### SubstituteMethodRmFromListOfGeneralUtilityRector

Use native php functions instead of GeneralUtility::rmFromList

- class: [`Ssch\TYPO3Rector\TYPO311\v3\SubstituteMethodRmFromListOfGeneralUtilityRector`](../rules/TYPO311/v3/SubstituteMethodRmFromListOfGeneralUtilityRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
 $element = '1';
 $list = '1,2,3';
-
-$newList = GeneralUtility::rmFromList($element, $list);
+$newList = implode(',', array_filter(explode(',', $list), function($item) use($element) {
+    return $element == $item;
+}));
```

<br>

### SwitchBehaviorOfArrayUtilityMethodsRector

Handles the methods `arrayDiffAssocRecursive()` and `arrayDiffKeyRecursive()` of ArrayUtility

- class: [`Ssch\TYPO3Rector\TYPO311\v3\SwitchBehaviorOfArrayUtilityMethodsRector`](../rules/TYPO311/v3/SwitchBehaviorOfArrayUtilityMethodsRector.php)

```diff
 $foo = ArrayUtility::arrayDiffAssocRecursive([], [], true);
-$bar = ArrayUtility::arrayDiffAssocRecursive([], [], false);
-$test = ArrayUtility::arrayDiffAssocRecursive([], []);
+$bar = ArrayUtility::arrayDiffKeyRecursive([], []);
+$test = ArrayUtility::arrayDiffKeyRecursive([], []);
```

<br>

### UniqueListFromStringUtilityRector

Use `StringUtility::uniqueList()` instead of GeneralUtility::uniqueList

- class: [`Ssch\TYPO3Rector\TYPO311\v0\UniqueListFromStringUtilityRector`](../rules/TYPO311/v0/UniqueListFromStringUtilityRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::uniqueList('1,2,2,3');
+use TYPO3\CMS\Core\Utility\StringUtility;
+StringUtility::uniqueList('1,2,2,3');
```

<br>

### UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector

Use php native function instead of GeneralUtility::shortMd5

- class: [`Ssch\TYPO3Rector\TYPO311\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector`](../rules/TYPO311/v4/UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
 $length = 10;
 $input = 'value';

-$shortMd5 = GeneralUtility::shortMD5($input, $length);
+$shortMd5 = substr(md5($input), 0, $length);
```

<br>

### UseNormalizedParamsToGetRequestUrlRector

Use normalized params to get the request url

- class: [`Ssch\TYPO3Rector\TYPO311\v3\UseNormalizedParamsToGetRequestUrlRector`](../rules/TYPO311/v3/UseNormalizedParamsToGetRequestUrlRector.php)

```diff
-$requestUri = $this->request->getRequestUri();
+$requestUri = $this->request->getAttribute('normalizedParams')->getRequestUrl();
```

<br>

## TYPO312

### AddMethodToWidgetInterfaceClassesRector

Add `getOptions()` to classes that implement the WidgetInterface

- class: [`Ssch\TYPO3Rector\TYPO312\v0\AddMethodToWidgetInterfaceClassesRector`](../rules/TYPO312/v0/AddMethodToWidgetInterfaceClassesRector.php)

```diff
 use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;

 class MyClass implements WidgetInterface
 {
     private readonly array $options;

     public function renderWidgetContent(): string
     {
         return 'foo';
     }
+
+    public function getOptions(): array
+    {
+        return $this->options;
+    }
 }
```

<br>

### ChangeExtbaseValidatorsRector

Adapt extbase validators to new interface

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ChangeExtbaseValidatorsRector`](../rules/TYPO312/v0/ChangeExtbaseValidatorsRector.php)

```diff
-final class MyCustomValidatorWithOptions implements ValidatorInterface
+use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;
+use TYPO3\CMS\Extbase\Validation\ValidatorResolver\ValidatorResolver;
+
+final class MyCustomValidatorWithoutOptions implements ValidatorInterface
 {
     private array $options;
     private \MyDependency $myDependency;

-    public function __construct(array $options, \MyDependency $myDependency)
+    public function __construct(\MyDependency $myDependency)
     {
-        $this->options = $options;
         $this->myDependency = $myDependency;
     }

     public function validate($value)
     {
         // Do something
     }

     public function getOptions(): array
     {
         return $this->options;
+    }
+
+    public function setOptions(array $options): void
+    {
+        $this->options = $options;
     }
 }
```

<br>

### ExtbaseAnnotationToAttributeRector

Change annotation to attribute

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ExtbaseAnnotationToAttributeRector`](../rules/TYPO312/v0/ExtbaseAnnotationToAttributeRector.php)

```diff
 use TYPO3\CMS\Extbase\Annotation as Extbase;

 class MyEntity
 {
-    /**
-    * @Extbase\ORM\Lazy()
-    * @Extbase\ORM\Transient()
-    */
+    #[Extbase\ORM\Lazy()]
+    #[Extbase\ORM\Transient()]
     protected string $myProperty
 }
```

<br>

### ImplementSiteLanguageAwareInterfaceRector

Implement SiteLanguageAwareInterface instead of using SiteLanguageAwareTrait

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ImplementSiteLanguageAwareInterfaceRector`](../rules/TYPO312/v0/ImplementSiteLanguageAwareInterfaceRector.php)

```diff
-use TYPO3\CMS\Core\Site\SiteLanguageAwareTrait;
+use TYPO3\CMS\Core\Site\SiteLanguageAwareInterface;
+use TYPO3\CMS\Core\Site\Entity\SiteLanguage;

-class MyClass
+class MyClass implements SiteLanguageAwareInterface
 {
-    use SiteLanguageAwareTrait;
+
+    protected SiteLanguage $siteLanguage;
+
+    public function setSiteLanguage(SiteLanguage $siteLanguage)
+    {
+        $this->siteLanguage = $siteLanguage;
+    }
+
+    public function getSiteLanguage(): SiteLanguage
+    {
+        return $this->siteLanguage;
+    }
 }
```

<br>

### MigrateColsToSizeForTcaTypeNoneRector

Migrates option cols to size for TCA type none

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateColsToSizeForTcaTypeNoneRector`](../rules/TYPO312/v0/MigrateColsToSizeForTcaTypeNoneRector.php)

```diff
 return [
     'columns' => [
         'aColumn' => [
             'config' => [
                 'type' => 'none',
-                'cols' => 20,
+                'size' => 20,
             ],
         ],
     ],
 ];
```

<br>

### MigrateContentObjectRendererLastTypoLinkPropertiesRector

Migrate lastTypoLink properties from ContentObjectRenderer

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateContentObjectRendererLastTypoLinkPropertiesRector`](../rules/TYPO312/v0/MigrateContentObjectRendererLastTypoLinkPropertiesRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkUrl;
-$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkTarget;
-$lastTypoLinkLD = $contentObjectRenderer->lastTypoLinkLD;
+$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkResult->getUrl();
+$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkResult->getTarget();
+$lastTypoLinkLD = ['target' => htmlspecialchars($contentObjectRenderer->lastTypoLinkResult->getTarget()), 'totalUrl' => $contentObjectRenderer->lastTypoLinkResult->getUrl(), 'type' => $contentObjectRenderer->lastTypoLinkResult->getType()];
```

<br>

### MigrateEvalIntAndDouble2ToTypeNumberRector

Migrate eval int and double2 to type number

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateEvalIntAndDouble2ToTypeNumberRector`](../rules/TYPO312/v0/MigrateEvalIntAndDouble2ToTypeNumberRector.php)

```diff
 return [
     'columns' => [
         'int_field' => [
             'label' => 'int field',
             'config' => [
-                'type' => 'input',
-                'eval' => 'int',
+                'type' => 'number',
             ],
         ],
         'double2_field' => [
             'label' => 'double2 field',
             'config' => [
-                'type' => 'input',
-                'eval' => 'double2',
+                'type' => 'number',
+                'format' => 'decimal',
             ],
         ],
     ],
 ];
```

<br>

### MigrateInputDateTimeRector

Migrate renderType inputDateTime to new TCA type datetime

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateInputDateTimeRector`](../rules/TYPO312/v0/MigrateInputDateTimeRector.php)

```diff
 return [
     'columns' => [
         'a_datetime_field' => [
              'label' => 'Datetime field',
              'config' => [
-                 'type' => 'input',
-                 'renderType' => 'inputDateTime',
+                 'type' => 'datetime',
+                 'format' => 'date',
                  'required' => true,
                  'size' => 20,
-                 'max' => 1024,
-                 'eval' => 'date,int',
                  'default' => 0,
              ],
         ],
     ],
 ];
```

<br>

### MigrateInternalTypeFolderToTypeFolderRector

Migrates TCA internal_type into new new TCA type folder

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateInternalTypeFolderToTypeFolderRector`](../rules/TYPO312/v0/MigrateInternalTypeFolderToTypeFolderRector.php)

```diff
 return [
     'columns' => [
         'columns' => [
             'aColumn' => [
                 'config' => [
-                    'type' => 'group',
-                    'internal_type' => 'folder',
+                    'type' => 'folder',
                 ],
             ],
             'bColumn' => [
                 'config' => [
                     'type' => 'group',
-                    'internal_type' => 'db',
                 ],
             ],
         ],
     ],
 ];
```

<br>

### MigrateItemsIndexedKeysToAssociativeRector

Migrates indexed item array keys to associative for type select, radio and check

- class: [`Ssch\TYPO3Rector\TYPO312\v3\MigrateItemsIndexedKeysToAssociativeRector`](../rules/TYPO312/v3/MigrateItemsIndexedKeysToAssociativeRector.php)

```diff
 return [
     'columns' => [
         'aColumn' => [
             'config' => [
                 'type' => 'select',
                 'renderType' => 'selectCheckBox',
                 'items' => [
-                    ['My label', 0, 'my-icon', 'group1', 'My Description'],
-                    ['My label 1', 1, 'my-icon', 'group1', 'My Description'],
-                    ['My label 2', 2, 'my-icon', 'group1', 'My Description'],
+                    ['label' => 'My label', 'value' => 0, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
+                    ['label' => 'My label 1', 'value' => 1, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
+                    ['label' => 'My label 2', 'value' => 2, 'icon' => 'my-icon', 'group' => 'group1', 'description' => 'My Description'],
                 ],
             ],
         ],
     ],
 ];
```

<br>

### MigrateMagicRepositoryMethodsRector

Migrate the magic findBy methods

- class: [`Ssch\TYPO3Rector\TYPO312\v3\MigrateMagicRepositoryMethodsRector`](../rules/TYPO312/v3/MigrateMagicRepositoryMethodsRector.php)

```diff
-$blogRepository->findByFooBar('bar');
-$blogRepository->findOneByFoo('bar');
-$blogRepository->countByFoo('bar');
+$blogRepository->findBy(['fooBar' => 'bar']);
+$blogRepository->findOneBy(['foo' => 'bar']);
+$blogRepository->count(['foo' => 'bar']);
```

<br>

### MigrateNullFlagRector

Migrate null flag

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateNullFlagRector`](../rules/TYPO312/v0/MigrateNullFlagRector.php)

```diff
 return [
     'columns' => [
         'nullable_column' => [
             'config' => [
-                'eval' => 'null',
+                'nullable' => true,
             ],
         ],
     ],
 ];
```

<br>

### MigratePasswordAndSaltedPasswordToPasswordTypeRector

Migrate password and salted password to password type

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigratePasswordAndSaltedPasswordToPasswordTypeRector`](../rules/TYPO312/v0/MigratePasswordAndSaltedPasswordToPasswordTypeRector.php)

```diff
 return [
     'columns' => [
         'password_field' => [
             'label' => 'Password',
             'config' => [
-                'type' => 'input',
-                'eval' => 'trim,password,saltedPassword',
+                'type' => 'password',
             ],
         ],
         'another_password_field' => [
             'label' => 'Password',
             'config' => [
-                'type' => 'input',
-                'eval' => 'trim,password',
+                'type' => 'password',
+                'hashed' => false,
             ],
         ],
     ],
 ];
```

<br>

### MigrateQueryBuilderExecuteRector

Replace `Querybuilder::execute()` with fitting methods

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateQueryBuilderExecuteRector`](../rules/TYPO312/v0/MigrateQueryBuilderExecuteRector.php)

```diff
 $rows = $queryBuilder
   ->select(...)
   ->from(...)
-  ->execute()
+  ->executeQuery()
   ->fetchAllAssociative();
 $deletedRows = $queryBuilder
   ->delete(...)
-  ->execute();
+  ->executeStatement();
```

<br>

### MigrateRenderTypeColorpickerToTypeColorRector

Migrate renderType colorpicker to type color

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateRenderTypeColorpickerToTypeColorRector`](../rules/TYPO312/v0/MigrateRenderTypeColorpickerToTypeColorRector.php)

```diff
 return [
     'columns' => [
         'a_color_field' => [
             'label' => 'Color field',
             'config' => [
-                'type' => 'input',
-                'renderType' => 'colorpicker',
+                'type' => 'color',
                 'required' => true,
                 'size' => 20,
-                'max' => 1024,
-                'eval' => 'trim',
                 'valuePicker' => [
                     'items' => [
                         ['typo3 orange', '#FF8700'],
                     ],
                 ],
             ],
         ],
     ],
 ];
```

<br>

### MigrateRequiredFlagRector

Migrate required flag

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateRequiredFlagRector`](../rules/TYPO312/v0/MigrateRequiredFlagRector.php)

```diff
 return [
     'columns' => [
         'required_column' => [
             'config' => [
-                'eval' => 'trim,required',
+                'eval' => 'trim',
+                'required' => true,
             ],
         ],
     ],
 ];
```

<br>

### MigrateToEmailTypeRector

Migrates existing input TCA with eval email to new TCA type email

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateToEmailTypeRector`](../rules/TYPO312/v0/MigrateToEmailTypeRector.php)

```diff
 return [
     'columns' => [
         'email_field' => [
             'label' => 'Email',
             'config' => [
-                'type' => 'input',
-                'eval' => 'trim,email',
-                'max' => 255,
+                'type' => 'email',
             ],
         ],
     ],
 ];
```

<br>

### RemoveCruserIdRector

Remove the TCA option cruser_id

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveCruserIdRector`](../rules/TYPO312/v0/RemoveCruserIdRector.php)

```diff
 return [
     'ctrl' => [
         'label' => 'foo',
-        'cruser_id' => 'cruser_id',
     ],
     'columns' => [
     ],
 ];
```

<br>

### RemoveMailerAdapterInterfaceRector

Refactor AdditionalFieldProvider classes

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveMailerAdapterInterfaceRector`](../rules/TYPO312/v0/RemoveMailerAdapterInterfaceRector.php)

```diff
-class RemoveMailerAdapterInterfaceFixture implements TYPO3\CMS\Mail\MailerAdapterInterface
+class RemoveMailerAdapterInterfaceFixture
```

<br>

### RemoveRelativeToCurrentScriptArgumentsRector

Removes all usages of the relativeToCurrentScript parameter

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveRelativeToCurrentScriptArgumentsRector`](../rules/TYPO312/v0/RemoveRelativeToCurrentScriptArgumentsRector.php)

```diff
 /** @var AudioTagRenderer $audioTagRenderer */
 $audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
-$foo = $audioTagRenderer->render($file, $width, $height, $options, $relative);
+$foo = $audioTagRenderer->render($file, $width, $height, $options);
```

<br>

### RemoveTCAInterfaceAlwaysDescriptionRector

Remove ['interface']['always_description']

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveTCAInterfaceAlwaysDescriptionRector`](../rules/TYPO312/v0/RemoveTCAInterfaceAlwaysDescriptionRector.php)

```diff
 return [
-    'interface' => [
-        'always_description' => 'foo,bar,baz',
-    ],
     'columns' => [
     ],
 ];
```

<br>

### RemoveTSFEConvOutputCharsetCallsRector

Removes usages of TSFE->convOutputCharset(...)

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveTSFEConvOutputCharsetCallsRector`](../rules/TYPO312/v0/RemoveTSFEConvOutputCharsetCallsRector.php)

```diff
 $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$foo = $GLOBALS['TSFE']->convOutputCharset($content);
-$bar = $tsfe->convOutputCharset('content');
+$foo = $content;
+$bar = 'content';
```

<br>

### RemoveTSFEMetaCharSetCallsRector

Removes calls to metaCharset property or methods of TSFE

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveTSFEMetaCharSetCallsRector`](../rules/TYPO312/v0/RemoveTSFEMetaCharSetCallsRector.php)

```diff
 $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$foo = $GLOBALS['TSFE']->metaCharset;
-$bar = $tsfe->metaCharset;
+$foo = 'utf-8';
+$bar = 'utf-8';
```

<br>

### RemoveTableLocalPropertyRector

Remove TCA property table_local in foreign_match_fields

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveTableLocalPropertyRector`](../rules/TYPO312/v0/RemoveTableLocalPropertyRector.php)

```diff
 use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

 return [
     'columns' => [
         'images' => [
             'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                 'images',
                 [
                     'foreign_match_fields' => [
                         'fieldname' => 'media',
                         'tablenames' => 'tx_site_domain_model_mediacollection',
-                        'table_local' => 'sys_file',
                     ],
                     'maxitems' => 1,
                     'minitems' => 1,
                 ],
                 $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
             ),
         ],
     ],
 ];
```

<br>

### RemoveUpdateRootlineDataRector

Remove unused `TemplateService->updateRootlineData()` calls

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveUpdateRootlineDataRector`](../rules/TYPO312/v0/RemoveUpdateRootlineDataRector.php)

```diff
-$templateService = GeneralUtility::makeInstance(TemplateService::class);
-$templateService->updateRootlineData();
+$templateService = GeneralUtility::makeInstance(TemplateService::class);
```

<br>

### ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector

Replace usages of `ContentObjectRenderer->getMailTo()` with `EmailLinkBuilder->processEmailLink()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector`](../rules/TYPO312/v0/ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector.php)

```diff
-$result = $cObj->getMailTo($mailAddress, $linktxt)
+$result = GeneralUtility::makeInstance(EmailLinkBuilder::class, $cObj, $cObj->getTypoScriptFrontendController())
+    ->processEmailLink((string)$mailAddress, (string)$linktxt);
```

<br>

### ReplaceExpressionBuilderMethodsRector

Replaces ExpressionBuilder methods `orX()` & `andX()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ReplaceExpressionBuilderMethodsRector`](../rules/TYPO312/v0/ReplaceExpressionBuilderMethodsRector.php)

```diff
 $rows = $queryBuilder
   ->select(...)
   ->from(...)
   ->where(
-    $queryBuilder->expr()->andX(...),
-    $queryBuilder->expr()->orX(...)
+    $queryBuilder->expr()->and(...),
+    $queryBuilder->expr()->or(...)
   )
   ->executeQuery()
   ->fetchAllAssociative();
```

<br>

### ReplacePageRepoOverlayFunctionRector

Replace `PageRepository->getRecordOverlay()` with `->getLanguageOverlay()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ReplacePageRepoOverlayFunctionRector`](../rules/TYPO312/v0/ReplacePageRepoOverlayFunctionRector.php)

```diff
-$pageRepo->getRecordOverlay('', [], '');
+$pageRepo->getLanguageOverlay('', []);
```

<br>

### ReplaceTSFECheckEnableFieldsRector

Replace TSFE calls to checkEnableFields with new RecordAccessVoter->accessGranted method

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ReplaceTSFECheckEnableFieldsRector`](../rules/TYPO312/v0/ReplaceTSFECheckEnableFieldsRector.php)

```diff
+use TYPO3\CMS\Core\Domain\Access\RecordAccessVoter\RecordAccessVoter;
 use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

 $row = [];

-$foo = $GLOBALS['TSFE']->checkEnableFields($row);
-$foofoo = $GLOBALS['TSFE']->checkPagerecordForIncludeSection($row);
+$foo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $GLOBALS['TSFE']->getContext());
+$foofoo = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $GLOBALS['TSFE']->getContext());

 /** @var TypoScriptFrontendController $typoscriptFrontendController */
 $typoscriptFrontendController = $GLOBALS['TSFE'];
-$bar = $typoscriptFrontendController->checkEnableFields($row);
-$baz = $typoscriptFrontendController->checkPagerecordForIncludeSection($row);
+$bar = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGranted('pages', $row, $typoscriptFrontendController->getContext());
+$baz = GeneralUtility::makeInstance(RecordAccessVoter::class)->accessGrantedForPageInRootLine($row, $typoscriptFrontendController->getContext());
```

<br>

### ReplaceTSFEWithContextMethodsRector

Replace TSFE with Context methods

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ReplaceTSFEWithContextMethodsRector`](../rules/TYPO312/v0/ReplaceTSFEWithContextMethodsRector.php)

```diff
-$GLOBALS['TSFE']->initUserGroups();
+$GLOBALS['TSFE']->getContext()->setAspect('frontend.user', $GLOBALS['TSFE']->fe_user->createUserAspect());

-$GLOBALS['TSFE']->isUserOrGroupSet();
+$GLOBALS['TSFE']->getContext()->getAspect('frontend.user')->isUserOrGroupSet();

-$GLOBALS['TSFE']->isBackendUserLoggedIn();
+$GLOBALS['TSFE']->getContext()->getPropertyFromAspect('backend.user', 'isLoggedIn', false);

-$GLOBALS['TSFE']->doWorkspacePreview();
+$GLOBALS['TSFE']->getContext()->getPropertyFromAspect('workspace', 'isOffline', false);

-$GLOBALS['TSFE']->whichWorkspace();
+$GLOBALS['TSFE']->getContext()->getPropertyFromAspect('workspace', 'id', 0);
```

<br>

### SubstituteCompositeExpressionAddMethodsRector

Replace `add()` and `addMultiple()` of CompositeExpression with `with()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\SubstituteCompositeExpressionAddMethodsRector`](../rules/TYPO312/v0/SubstituteCompositeExpressionAddMethodsRector.php)

```diff
 $compositeExpression = CompositeExpression::or();

-$compositeExpression->add(
+$compositeExpression = $compositeExpression->with(
     $queryBuilder->expr()->eq(
         'field',
         $queryBuilder->createNamedParameter('foo')
     )
 );

-$compositeExpression->addMultiple(
-    [
+$compositeExpression = $compositeExpression->with(
+    ...[
         $queryBuilder->expr()->eq(
             'field',
             $queryBuilder->createNamedParameter('bar')
         ),
         $queryBuilder->expr()->eq(
             'field',
             $queryBuilder->createNamedParameter('baz')
         ),
     ]
 );
```

<br>

### UseCompositeExpressionStaticMethodsRector

Use CompositeExpression static methods instead of constructor

- class: [`Ssch\TYPO3Rector\TYPO312\v0\UseCompositeExpressionStaticMethodsRector`](../rules/TYPO312/v0/UseCompositeExpressionStaticMethodsRector.php)

```diff
-$compositeExpressionAND = new CompositeExpression(CompositeExpression::TYPE_AND, []);
-$compositeExpressionOR = new CompositeExpression(CompositeExpression::TYPE_OR, []);
+$compositeExpressionAND = CompositeExpression::and([]);
+$compositeExpressionOR = CompositeExpression::or([]);
```

<br>

### UseConfigArrayForTSFEPropertiesRector

Use config array of TSFE instead of properties

- class: [`Ssch\TYPO3Rector\TYPO312\v0\UseConfigArrayForTSFEPropertiesRector`](../rules/TYPO312/v0/UseConfigArrayForTSFEPropertiesRector.php)

```diff
-$fileTarget = $GLOBALS['TSFE']->fileTarget;
+$fileTarget = $GLOBALS['TSFE']->config['config']['fileTarget'];
```

<br>

### UsePageDoktypeRegistryRector

Migrate from `$GLOBALS['PAGES_TYPES']` to the new PageDoktypeRegistry

- class: [`Ssch\TYPO3Rector\TYPO312\v0\UsePageDoktypeRegistryRector`](../rules/TYPO312/v0/UsePageDoktypeRegistryRector.php)

```diff
-$GLOBALS['PAGES_TYPES'][116] = [
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry;
+GeneralUtility::makeInstance(PageDoktypeRegistry::class)->add(116, [
     'type' => 'web',
     'allowedTables' => '*',
-];
+]);
```

<br>
