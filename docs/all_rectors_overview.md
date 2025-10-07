# 203 Rules Overview

<br>

## Categories

- [CodeQuality](#codequality) (11)

- [General](#general) (3)

- [TYPO310](#typo310) (37)

- [TYPO311](#typo311) (34)

- [TYPO312](#typo312) (58)

- [TYPO313](#typo313) (46)

- [TYPO314](#typo314) (13)

- [TypeDeclaration](#typedeclaration) (1)

<br>

## CodeQuality

### AddErrorCodeToExceptionRector

Add timestamp error code to exceptions

- class: [`Ssch\TYPO3Rector\CodeQuality\General\AddErrorCodeToExceptionRector`](../rules/CodeQuality/General/AddErrorCodeToExceptionRector.php)

```diff
-throw new \RuntimeException('my message');
+throw new \RuntimeException('my message', 1729021897);
```

<br>

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
     'autoload' => [
         'psr-4' => [
             'Foo\\Bar\\' => 'Classes/',
         ],
     ],
-    '_md5_values_when_last_written' => 'a:0:{}',
 ];
```

<br>

### GeneralUtilityMakeInstanceToConstructorPropertyRector

Move GeneralUtility::makeInstance calls to constructor injection

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\CodeQuality\General\GeneralUtilityMakeInstanceToConstructorPropertyRector`](../rules/CodeQuality/General/GeneralUtilityMakeInstanceToConstructorPropertyRector.php)

```diff
 use TYPO3\CMS\Core\Context\Context;
-use TYPO3\CMS\Core\Utility\GeneralUtility;

 class Service
 {
+    private Context $context;
+
+    public function __construct(Context $context)
+    {
+        $this->context = $context;
+    }
+
     public function myMethod(): void
     {
-        GeneralUtility::makeInstance(Context::class)->getAspect('frontend.user');
+        $this->context->getAspect('frontend.user');
     }
 }
```

<br>

### InjectMethodToConstructorInjectionRector

Replace inject method to constructor injection

- class: [`Ssch\TYPO3Rector\CodeQuality\General\InjectMethodToConstructorInjectionRector`](../rules/CodeQuality/General/InjectMethodToConstructorInjectionRector.php)

```diff
 use TYPO3\CMS\Core\Cache\CacheManager;

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

Use `GeneralUtility::makeInstance()` instead of `getInstance` call

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector`](../rules/CodeQuality/General/MethodGetInstanceToMakeInstanceCallRector.php)

```diff
-$instance = TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
+use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
+
+$instance = GeneralUtility::makeInstance(ExtractorRegistry::class);
```

<br>

### MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector

Move `ExtensionManagementUtility::addStaticFile()` into Configuration/TCA/Overrides/sys_template.php

- class: [`Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector`](../rules/CodeQuality/General/MoveExtensionManagementUtilityAddStaticFileIntoTCAOverridesRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('extensionKey', 'Configuration/TypoScript', 'Title');
+// Move to file Configuration/TCA/Overrides/sys_template.php
```

<br>

### MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector

Move `ExtensionManagementUtility::addToAllTCAtypes()` into table specific Configuration/TCA/Overrides file

- class: [`Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector`](../rules/CodeQuality/General/MoveExtensionManagementUtilityAddToAllTCAtypesIntoTCAOverridesRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('table', 'new_field', '', 'after:existing_field');
+// Move to table specific Configuration/TCA/Overrides/table.php file
```

<br>

### MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector

Move `ExtensionUtility::registerPlugin()` into Configuration/TCA/Overrides/tt_content.php

- class: [`Ssch\TYPO3Rector\CodeQuality\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector`](../rules/CodeQuality/General/MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector.php)

```diff
-\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('extension_key', 'Pi1', 'Title');
+// Move to file Configuration/TCA/Overrides/tt_content.php
```

<br>

### RenameClassMapAliasRector

Replace defined classes by new ones

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

### UseExtensionKeyInLocalizationUtilityRector

Replace the second parameter of `LocalizationUtility::translate()` with the extension name

- class: [`Ssch\TYPO3Rector\CodeQuality\General\UseExtensionKeyInLocalizationUtilityRector`](../rules/CodeQuality/General/UseExtensionKeyInLocalizationUtilityRector.php)

```diff
-LocalizationUtility::translate('key', 'extension_key');
+LocalizationUtility::translate('key', 'ExtensionName');
```

<br>

## General

### ConstantsToBackedEnumRector

Migrate constants to enum class

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumRector`](../rules/General/Renaming/ConstantsToBackedEnumRector.php)

```diff
-\TYPO3\CMS\Core\Imaging\Icon::SIZE_DEFAULT
-\TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL
-\TYPO3\CMS\Core\Imaging\Icon::SIZE_MEDIUM
-\TYPO3\CMS\Core\Imaging\Icon::SIZE_LARGE
-\TYPO3\CMS\Core\Imaging\Icon::SIZE_MEGA
+TYPO3\CMS\Core\Imaging\IconSize::DEFAULT
+TYPO3\CMS\Core\Imaging\IconSize::SMALL
+TYPO3\CMS\Core\Imaging\IconSize::MEDIUM
+TYPO3\CMS\Core\Imaging\IconSize::LARGE
+TYPO3\CMS\Core\Imaging\IconSize::MEGA
```

<br>

### ConstantsToBackedEnumValueRector

Migrate constants to enum class values

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\General\Renaming\ConstantsToBackedEnumValueRector`](../rules/General/Renaming/ConstantsToBackedEnumValueRector.php)

```diff
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_UNKNOWN
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_TEXT
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_IMAGE
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_AUDIO
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_VIDEO
-\TYPO3\CMS\Core\Resource\AbstractFile::FILETYPE_APPLICATION
+\TYPO3\CMS\Core\Resource\FileType::UNKNOWN->value
+\TYPO3\CMS\Core\Resource\FileType::TEXT->value
+\TYPO3\CMS\Core\Resource\FileType::IMAGE->value
+\TYPO3\CMS\Core\Resource\FileType::AUDIO->value
+\TYPO3\CMS\Core\Resource\FileType::VIDEO->value
+\TYPO3\CMS\Core\Resource\FileType::APPLICATION->value
```

<br>

### RenameAttributeRector

Rename Attribute

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\General\Renaming\RenameAttributeRector`](../rules/General/Renaming/RenameAttributeRector.php)

```diff
-#[Controller]
+#[AsController]
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

Change parameter `$excludeServiceKeys` explicitly to an array

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
-$myVariable = $GLOBALS['TSFE']->forceTemplateParsing;
-$myVariable2 = $GLOBALS['TSFE']->tmpl->forceTemplateParsing;
+$myVariable = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');
+$myVariable2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('typoscript', 'forcedTemplateParsing');

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

Use native function `idn_to_ascii` instead of `GeneralUtility::idnaEncode()`

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
 \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
-   'TYPO3.CMS.Form',
+   'Form',
    'Formframework',
    'Form',
    'content-form'
 );
```

<br>

### RemoveEnableMultiSelectFilterTextfieldRector

Remove `"enableMultiSelectFilterTextfield" => true` as its default from render type "selectMultipleSideBySide"

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

Remove constants `FORMAT_PLAINTEXT` and `FORMAT_HTML` of class `TYPO3\CMS\Form\Domain\Finishers\EmailFinisher`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemoveFormatConstantsEmailFinisherRector`](../rules/TYPO310/v0/RemoveFormatConstantsEmailFinisherRector.php)

```diff
-$this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
+$this->setOption('addHtmlPart', true);
```

<br>

### RemovePropertyExtensionNameRector

Use method `getControllerExtensionName()` from `$request` property instead of removed property `$extensionName`

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

### RemoveShowRemovedLocalizationRecordsRector

Remove showRemovedLocalizationRecords from inline TCA configuration

- class: [`Ssch\TYPO3Rector\TYPO310\v0\RemoveShowRemovedLocalizationRecordsRector`](../rules/TYPO310/v0/RemoveShowRemovedLocalizationRecordsRector.php)

```diff
 return [
     'columns' => [
         'falFileRelation' => [
             'config' => [
                 'type' => 'inline',
                 'appearance' => [
                     'showPossibleLocalizationRecords' => false,
-                    'showRemovedLocalizationRecords' => false,
                 ],
             ],
         ],
     ],
 ];
```

<br>

### RemoveTcaOptionSetToDefaultOnCopyRector

Remove TCA option "setToDefaultOnCopy"

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

Refactor `ContentObjectRenderer::sendNotifyEmail()` to MailMessage API

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

Substitute `ResourceFactory::getInstance()` with `GeneralUtility::makeInstance(ResourceFactory::class)`

- class: [`Ssch\TYPO3Rector\TYPO310\v3\SubstituteResourceFactoryRector`](../rules/TYPO310/v3/SubstituteResourceFactoryRector.php)

```diff
-$resourceFactory = ResourceFactory::getInstance();
+$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
```

<br>

### SwiftMailerBasedMailMessageToMailerBasedMessageRector

New Mail API based on `symfony/mailer` and `symfony/mime`

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

Migrate `GeneralUtility::verifyFilenameAgainstDenyPattern()` to `FileNameValidator->isValid()`

- class: [`Ssch\TYPO3Rector\TYPO310\v4\UnifiedFileNameValidatorRector`](../rules/TYPO310/v4/UnifiedFileNameValidatorRector.php)

```diff
+use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $filename = 'somefile.php';
-if (!GeneralUtility::verifyFilenameAgainstDenyPattern($filename)) {
+if (!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)) {
 }

-if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FILE_DENY_PATTERN_DEFAULT) {
+if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FileNameValidator::DEFAULT_FILE_DENY_PATTERN) {
 }
```

<br>

### UseActionControllerRector

Use ActionController class instead of AbstractController

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

### UseConstantsFromTYPO3DatabaseConnection

Use strict types in Extbase ActionController

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseConstantsFromTYPO3DatabaseConnection`](../rules/TYPO310/v0/UseConstantsFromTYPO3DatabaseConnection.php)

```diff
+use TYPO3\CMS\Core\Database\Connection;
+
 $queryBuilder = $this->connectionPool->getQueryBuilderForTable('table');
 $result = $queryBuilder
     ->select('uid')
     ->from('table')
     ->where(
-        $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', \PDO::PARAM_STR)),
-        $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, \PDO::PARAM_INT)),
-        $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, \PDO::PARAM_BOOL)),
-        $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(true, \PDO::PARAM_NULL))
+        $queryBuilder->expr()->eq('bodytext', $queryBuilder->createNamedParameter('lorem', Connection::PARAM_STR)),
+        $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter(42, Connection::PARAM_INT)),
+        $queryBuilder->expr()->eq('available', $queryBuilder->createNamedParameter(true, Connection::PARAM_BOOL)),
+        $queryBuilder->expr()->eq('foo', $queryBuilder->createNamedParameter(true, Connection::PARAM_NULL))
     )
     ->executeQuery();
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

Rewrite method calls of `GeneralUtility::getUrl("somefile.csv")` to `@file_get_contents()`

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

Turn `TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin` calls to native php `hex2bin()`

- class: [`Ssch\TYPO3Rector\TYPO310\v0\UseNativePhpHex2binMethodRector`](../rules/TYPO310/v0/UseNativePhpHex2binMethodRector.php)

```diff
-TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br>

### UseTwoLetterIsoCodeFromSiteLanguageRector

Use `SiteLanguage->getTwoLetterIsoCode()` instead of `$GLOBALS['TSFE']->sys_language_isocode`

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

Use `BackendUtility::getClickMenuOnIconTagParameters()` instead of `BackendUtility::wrapClickMenuOnIcon()`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\GetClickMenuOnIconTagParametersRector`](../rules/TYPO311/v0/GetClickMenuOnIconTagParametersRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
 $returnTagParameters = true;
-BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
+BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
```

<br>

### HandleCObjRendererATagParamsMethodRector

Remove deprecated params of the `ContentObjectRenderer->getATagParams()` method

- class: [`Ssch\TYPO3Rector\TYPO311\v5\HandleCObjRendererATagParamsMethodRector`](../rules/TYPO311/v5/HandleCObjRendererATagParamsMethodRector.php)

```diff
 $cObjRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
-$bar = $cObjRenderer->getATagParams([], false);
+$bar = $cObjRenderer->getATagParams([]);
```

<br>

### MigrateAbstractUserAuthenticationCreateSessionIdRector

Migrate `FrontendUserAuthentication->createSessionId()` and `BackendUserAuthentication->createSessionId()` to `Random->generateRandomHexString(32)`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationCreateSessionIdRector`](../rules/TYPO311/v0/MigrateAbstractUserAuthenticationCreateSessionIdRector.php)

```diff
-$frontendUserAuthentication = new \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication();
-$sessionId = $frontendUserAuthentication->createSessionId();
+$sessionId = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Random::class)->generateRandomHexString(32);
```

<br>

```diff
-$backendUserAuthentication = new \TYPO3\CMS\Core\Authentication\BackendUserAuthentication();
-$sessionId = $backendUserAuthentication->createSessionId();
+$sessionId = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(Random::class)->generateRandomHexString(32);
```

<br>

### MigrateAbstractUserAuthenticationGetIdRector

Migrate `FrontendUserAuthentication->id` and `BackendUserAuthentication->id`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationGetIdRector`](../rules/TYPO311/v0/MigrateAbstractUserAuthenticationGetIdRector.php)

```diff
 $frontendUserAuthentication = new FrontendUserAuthentication();
-$id = $frontendUserAuthentication->id;
+$id = $frontendUserAuthentication->getSession()->getIdentifier();
```

<br>

```diff
 $backendUserAuthentication = new BackendUserAuthentication();
-$id = $backendUserAuthentication->id;
+$id = $backendUserAuthentication->getSession()->getIdentifier();
```

<br>

### MigrateAbstractUserAuthenticationGetSessionIdRector

Migrate `FrontendUserAuthentication->getSessionId()` and `BackendUserAuthentication->getSessionId()`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\MigrateAbstractUserAuthenticationGetSessionIdRector`](../rules/TYPO311/v0/MigrateAbstractUserAuthenticationGetSessionIdRector.php)

```diff
 $frontendUserAuthentication = new FrontendUserAuthentication();
-$id = $frontendUserAuthentication->getSessionId();
+$id = $frontendUserAuthentication->getSession()->getIdentifier();
```

<br>

```diff
 $backendUserAuthentication = new BackendUserAuthentication();
-$id = $backendUserAuthentication->getSessionId();
+$id = $backendUserAuthentication->getSession()->getIdentifier();
```

<br>

### MigrateExtbaseViewInterfaceRector

Migrate Extbase ViewInterface

- class: [`Ssch\TYPO3Rector\TYPO311\v5\MigrateExtbaseViewInterfaceRector`](../rules/TYPO311/v5/MigrateExtbaseViewInterfaceRector.php)

```diff
 class MyClass
 {
-    protected function initializeView(ViewInterface $view)
+    /**
+     * @param \TYPO3Fluid\Fluid\View\ViewInterface $view
+     */
+    protected function initializeView($view)
     {
-        parent::initializeView($view);
     }
 }
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

### MigrateHttpUtilityRedirectRector

Migrate `HttpUtilty::redirect()` to ResponseFactory

- class: [`Ssch\TYPO3Rector\TYPO311\v3\MigrateHttpUtilityRedirectRector`](../rules/TYPO311/v3/MigrateHttpUtilityRedirectRector.php)

```diff
+use Psr\Http\Message\ResponseFactoryInterface;
+use TYPO3\CMS\Core\Http\PropagateResponseException;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Core\Utility\HttpUtility;

-HttpUtility::redirect('https://example.com', HttpUtility::HTTP_STATUS_303);
+$response = GeneralUtility::makeInstance(ResponseFactoryInterface::class)
+    ->createResponse(HttpUtility::HTTP_STATUS_303)
+    ->withAddedHeader('location', 'https://example.com');
+throw new PropagateResponseException($response);
```

<br>

### MigrateLanguageFieldToTcaTypeLanguageRector

Use the new TCA type language instead of foreign_table => sys_language for selecting a records

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

### MigrateRootUidToStartingPointsRector

Migrate `[treeConfig][rootUid]` to `[treeConfig][startingPoints]`

- class: [`Ssch\TYPO3Rector\TYPO311\v4\MigrateRootUidToStartingPointsRector`](../rules/TYPO311/v4/MigrateRootUidToStartingPointsRector.php)

```diff
 return [
     'columns' => [
         'aField' => [
             'config' => [
                 'type' => 'select',
                 'renderType' => 'selectTree',
                 'treeConfig' => [
-                    'rootUid' => 42
+                    'startingPoints' => '42'
                 ],
             ],
         ],
     ],
 ];
```

<br>

### MigrateSpecialLanguagesToTcaTypeLanguageRector

Use the new TCA type language instead of foreign_table => sys_language for selecting a records

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

Replace `public $cObj` with `protected` and set via method

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

### RemoveTypeHintViewInterfaceRector

RemoveTypeHintViewInterfaceRector is deprecated.

- class: [`Ssch\TYPO3Rector\TYPO311\v5\RemoveTypeHintViewInterfaceRector`](../rules/TYPO311/v5/RemoveTypeHintViewInterfaceRector.php)

```diff
-Do not use this rule any more. Please use MigrateExtbaseViewInterfaceRector instead.
+Do not use this rule any more. Please use MigrateExtbaseViewInterfaceRector instead!
```

<br>

### RemoveWorkspacePlaceholderShadowColumnsConfigurationRector

Remove Workspace Placeholder Shadow Columns Configuration

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

Turn properties with `@TYPO3\CMS\Extbase\Annotation\Inject` to setter injection

- class: [`Ssch\TYPO3Rector\TYPO311\v0\ReplaceInjectAnnotationWithMethodRector`](../rules/TYPO311/v0/ReplaceInjectAnnotationWithMethodRector.php)

```diff
 class MyClass
 {
     /**
      * @var SomeService
-     * @TYPO3\CMS\Extbase\Annotation\Inject
      */
     private $someService;
+
+    public function injectSomeService(SomeService $someService)
+    {
+        $this->someService = $someService;
+    }
 }
```

<br>

### ReplaceTSFEATagParamsCallOnGlobalsRector

Replace direct calls to `$GLOBALS['TSFE']->ATagParams`

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

Substitute `TYPO3_MODE` and `TYPO3_REQUESTTYPE` constants

- class: [`Ssch\TYPO3Rector\TYPO311\v0\SubstituteConstantsModeAndRequestTypeRector`](../rules/TYPO311/v0/SubstituteConstantsModeAndRequestTypeRector.php)

```diff
-defined('TYPO3_MODE') or die();
+defined('TYPO3') or die();
```

<br>

```diff
-if (TYPO3_MODE === 'FE') {
+use TYPO3\CMS\Core\Http\ApplicationType;
+
+if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
     // Do something
 }
-if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE) {
+if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
     // Do something
 }
```

<br>

```diff
-if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_BE)) {
+use TYPO3\CMS\Core\Http\ApplicationType;
+
+if (!(ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend())) {
     // Do something
 }
```

<br>

```diff
-if (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_CLI) {
+use TYPO3\CMS\Core\Core\Environment;
+
+if (Environment::isCli()) {
     // Do something
 }
```

<br>

### SubstituteEnvironmentServiceWithApplicationTypeRector

Substitute class EnvironmentService with ApplicationType class

- class: [`Ssch\TYPO3Rector\TYPO311\v2\SubstituteEnvironmentServiceWithApplicationTypeRector`](../rules/TYPO311/v2/SubstituteEnvironmentServiceWithApplicationTypeRector.php)

```diff
-if ($this->environmentService->isEnvironmentInFrontendMode()) {
+if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend())
     ...
 }
```

<br>

### SubstituteExtbaseRequestGetBaseUriRector

Use PSR-7 compatible request for uri instead of the method `getBaseUri()`

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
     private ModuleTemplateFactory $moduleTemplateFactory;
+    private IconFactory $iconFactory;
+    private PageRenderer $pageRenderer;

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

Use native php functions instead of `GeneralUtility::rmFromList()`

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

Use `StringUtility::uniqueList()` instead of `GeneralUtility::uniqueList()`

- class: [`Ssch\TYPO3Rector\TYPO311\v0\UniqueListFromStringUtilityRector`](../rules/TYPO311/v0/UniqueListFromStringUtilityRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::uniqueList('1,2,2,3');
+use TYPO3\CMS\Core\Utility\StringUtility;
+StringUtility::uniqueList('1,2,2,3');
```

<br>

### UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector

Use php native function instead of `GeneralUtility::shortMd5()`

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
 use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

-final class MyCustomValidatorWithOptions implements ValidatorInterface
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

### CommandConfigurationToAttributeRector

Use Symfony attribute to autoconfigure cli commands

- class: [`Ssch\TYPO3Rector\TYPO312\v4\CommandConfigurationToAttributeRector`](../rules/TYPO312/v4/CommandConfigurationToAttributeRector.php)

```diff
 use Symfony\Component\Console\Command\Command;
+use Symfony\Component\Console\Attribute\AsCommand;
+#[AsCommand(name: 'my_special_command')]
 class MySpecialCommand extends Command
 {
 }
```

<br>

### ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector

Extbase controller actions with redirects must return ResponseInterface

- class: [`Ssch\TYPO3Rector\TYPO312\v0\ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector`](../rules/TYPO312/v0/ExtbaseActionsWithRedirectMustReturnResponseInterfaceRector.php)

```diff
+use Psr\Http\Message\ResponseInterface;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyController extends ActionController
 {
-    public function someAction()
+    public function someAction(): ResponseInterface
     {
-        $this->redirect('foo', 'bar');
+        return $this->redirect('foo', 'bar');
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
-     * @Extbase\ORM\Lazy()
-     * @Extbase\ORM\Transient()
-     */
+    #[Extbase\ORM\Lazy()]
+    #[Extbase\ORM\Transient()]
     protected string $myProperty;
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

### MigrateBackendModuleRegistrationRector

Migrate Backend Module Registration

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateBackendModuleRegistrationRector`](../rules/TYPO312/v0/MigrateBackendModuleRegistrationRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
-    'web',
-    'example',
-    'top',
-    '',
-    [
-        'routeTarget' => MyExampleModuleController::class . '::handleRequest',
-        'name' => 'web_example',
+// Configuration/Backend/Modules.php
+return [
+    'web_module' => [
+        'parent' => 'web',
+        'position' => ['before' => '*'],
         'access' => 'admin',
-        'workspaces' => 'online',
+        'workspaces' => 'live',
+        'path' => '/module/web/example',
         'iconIdentifier' => 'module-example',
+        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
         'labels' => 'LLL:EXT:example/Resources/Private/Language/locallang_mod.xlf',
-        'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
-    ]
-);
-\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
-    'Extkey',
-    'web',
-    'example',
-    'after:info',
-    [
-        MyExtbaseExampleModuleController::class => 'list, detail',
+        'routes' => [
+            '_default' => [
+                'target' => MyExampleModuleController::class . '::handleRequest',
+            ],
+        ],
     ],
-    [
+    'web_ExtkeyExample' => [
+        'parent' => 'web',
+        'position' => ['after' => 'web_info'],
         'access' => 'admin',
-        'workspaces' => 'online',
+        'workspaces' => 'live',
         'iconIdentifier' => 'module-example',
+        'path' => '/module/web/ExtkeyExample',
         'labels' => 'LLL:EXT:extkey/Resources/Private/Language/locallang_mod.xlf',
-    ]
-);
+        'extensionName' => 'Extkey',
+        'controllerActions' => [
+            MyExtbaseExampleModuleController::class => [
+                'list',
+                'detail'
+            ],
+        ],
+    ],
+];
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

### MigrateConfigurationManagerGetContentObjectRector

Migrate `ConfigurationManager->getContentObject()` to use request attribute instead

- class: [`Ssch\TYPO3Rector\TYPO312\v4\MigrateConfigurationManagerGetContentObjectRector`](../rules/TYPO312/v4/MigrateConfigurationManagerGetContentObjectRector.php)

```diff
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod(): void
     {
-        $contentObject = $this->configurationManager->getContentObject();
+        $contentObject = $this->request->getAttribute('currentContentObject');
     }
 }
```

<br>

### MigrateContentObjectRendererGetTypoLinkUrlRector

Migrate `ContentObjectRenderer->getTypoLink_URL()` to `ContentObjectRenderer->createUrl()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateContentObjectRendererGetTypoLinkUrlRector`](../rules/TYPO312/v0/MigrateContentObjectRendererGetTypoLinkUrlRector.php)

```diff
-$contentObjectRenderer->typoLink_URL(12);
+$contentObjectRenderer->createUrl(['parameter' => 12]);
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

### MigrateFetchAllToFetchAllAssociativeRector

Migrate `->fetchAll()` to `->fetchAllAssociative()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchAllToFetchAllAssociativeRector`](../rules/TYPO312/v0/MigrateFetchAllToFetchAllAssociativeRector.php)

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetchAll();
+  ->fetchAllAssociative();
```

<br>

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetchAll(FetchMode::NUMERIC);
+  ->fetchAllNumeric();
```

<br>

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetchAll(FetchMode::COLUMN);
+  ->fetchFirstColumn();
```

<br>

### MigrateFetchColumnToFetchOneRector

Migrate `->fetchColumn(0)` to `->fetchOne()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchColumnToFetchOneRector`](../rules/TYPO312/v0/MigrateFetchColumnToFetchOneRector.php)

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetchColumn(0);
+  ->fetchOne(0);
```

<br>

### MigrateFetchToFetchAssociativeRector

Migrate `->fetch()` to `->fetchAssociative()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateFetchToFetchAssociativeRector`](../rules/TYPO312/v0/MigrateFetchToFetchAssociativeRector.php)

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetch();
+  ->fetchAssociative();
```

<br>

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetch(FetchMode::NUMERIC);
+  ->fetchNumeric();
```

<br>

```diff
 $result = $queryBuilder
   ->select(...)
   ->from(...)
   ->executeQuery()
-  ->fetch(FetchMode::COLUMN);
+  ->fetchOne();
```

<br>

### MigrateFileFieldTCAConfigToTCATypeFileRector

Migrate method `ExtensionManagementUtility::getFileFieldTCAConfig()` to TCA type file

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateFileFieldTCAConfigToTCATypeFileRector`](../rules/TYPO312/v0/MigrateFileFieldTCAConfigToTCATypeFileRector.php)

```diff
 return [
     'columns' => [
         'image_field' => [
-            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
-                'logo',
-                [
-                    'maxitems' => 1,
-                    'appearance' => [
-                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
-                        'fileUploadAllowed' => 0
-                    ],
-                    'overrideChildTca' => [
-                        'types' => [
-                            '0' => [
-                                'showitem' => '
+            'config' => [
+                'type' => 'file',
+                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
+                'maxitems' => 1,
+                'appearance' => [
+                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
+                    'fileUploadAllowed' => 0
+                ],
+                'overrideChildTca' => [
+                    'types' => [
+                        '0' => [
+                            'showitem' => '
                             --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                             --palette--;;filePalette'
-                            ],
-                            AbstractFile::FILETYPE_IMAGE => [
-                                'showitem' => '
+                        ],
+                        AbstractFile::FILETYPE_IMAGE => [
+                            'showitem' => '
                             --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                             --palette--;;filePalette'
-                            ],
                         ],
                     ],
                 ],
-                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
-            ),
+            ],
         ],
     ],
 ];
```

<br>

### MigrateGeneralUtilityGPMergedRector

Migrate `GeneralUtility::_GPmerged()` to use PSR-7 ServerRequest instead

- class: [`Ssch\TYPO3Rector\TYPO312\v2\MigrateGeneralUtilityGPMergedRector`](../rules/TYPO312/v2/MigrateGeneralUtilityGPMergedRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\ArrayUtility;

-$getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
+$getMergedWithPost = $request->getQueryParams()['tx_scheduler'];
+ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $request->getParsedBody()['tx_scheduler']);
```

<br>

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Core\Utility\ArrayUtility;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod(): void
     {
-        $getMergedWithPost = GeneralUtility::_GPmerged('tx_scheduler');
+        $getMergedWithPost = $this->request->getQueryParams()['tx_scheduler'];
+        ArrayUtility::mergeRecursiveWithOverrule($getMergedWithPost, $this->request->getParsedBody()['tx_scheduler']);
     }
 }
```

<br>

### MigrateGeneralUtilityGPRector

Migrate `GeneralUtility::_GP()` to use PSR-7 ServerRequest instead

- class: [`Ssch\TYPO3Rector\TYPO312\v3\MigrateGeneralUtilityGPRector`](../rules/TYPO312/v3/MigrateGeneralUtilityGPRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-$value = GeneralUtility::_GP('tx_scheduler');
+$value = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler'] ?? $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;
```

<br>

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod(): void
     {
-        $value = GeneralUtility::_GP('tx_scheduler');
+        $value = $this->request->getParsedBody()['tx_scheduler'] ?? $this->request->getQueryParams()['tx_scheduler'] ?? null;
     }
 }
```

<br>

### MigrateGetControllerContextGetUriBuilderRector

Migrate extbase controller calls `$this->getControllerContext()->getUriBuilder();` to ->uriBuilder

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateGetControllerContextGetUriBuilderRector`](../rules/TYPO312/v0/MigrateGetControllerContextGetUriBuilderRector.php)

```diff
 use Psr\Http\Message\ResponseInterface;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class DummyController extends ActionController
 {
     public function showAction(): ResponseInterface
     {
-        $url = $this->getControllerContext()->getUriBuilder()
+        $url = $this->uriBuilder
             ->setTargetPageType(10002)
             ->uriFor('addresses');
     }
 }
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

Migrate indexed item array keys to associative for type select, radio and check

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

### MigrateRecordTooltipMethodToRecordIconAltTextMethodRector

Migrate `BackendUtility::getRecordToolTip()` to `BackendUtility::getRecordIconAltText()`

- class: [`Ssch\TYPO3Rector\TYPO312\v4\MigrateRecordTooltipMethodToRecordIconAltTextMethodRector`](../rules/TYPO312/v4/MigrateRecordTooltipMethodToRecordIconAltTextMethodRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;

-$link = '<a href="#" ' . BackendUtility::getRecordToolTip('tooltip') . '>my link</a>';
+$link = '<a href="#" title="' . BackendUtility::getRecordIconAltText('tooltip') . '">my link</a>';
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

### MigrateRenderTypeInputLinkToTypeLinkRector

migrate renderType inputLink to new tca field type link

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateRenderTypeInputLinkToTypeLinkRector`](../rules/TYPO312/v0/MigrateRenderTypeInputLinkToTypeLinkRector.php)

```diff
 return [
     'ctrl' => [],
     'columns' => [
         'full_example' => [
             'config' => [
-                'type' => 'input',
-                'renderType' => 'inputLink',
+                'type' => 'link',
                 'required' => true,
                 'size' => 21,
-                'max' => 1234,
-                'eval' => 'trim,null',
-                'fieldControl' => [
-                    'linkPopup' => [
-                        'disabled' => true,
-                        'options' => [
-                            'title' => 'Browser title',
-                            'allowedExtensions' => 'jpg,png',
-                            'blindLinkFields' => 'class,target,title',
-                            'blindLinkOptions' => 'mail,folder,file,telephone',
-                        ],
-                    ],
+                'eval' => 'null',
+                'allowedTypes' => ['page', 'url', 'record'],
+                'appearance' => [
+                    'enableBrowser' => false,
+                    'browserTitle' => 'Browser title',
+                    'allowedOptions' => ['params', 'rel'],
+                    'allowedFileExtensions' => ['jpg', 'png']
                 ],
-                'softref' => 'typolink',
             ],
         ],
     ],
 ];
```

<br>

### MigrateRequestArgumentFromMethodStartRector

Use method setRequest of ContentObjectRenderer instead of third argument of method start

- class: [`Ssch\TYPO3Rector\TYPO312\v4\MigrateRequestArgumentFromMethodStartRector`](../rules/TYPO312/v4/MigrateRequestArgumentFromMethodStartRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->start([], 'pages', $GLOBALS['TYPO3_REQUEST']);
+$contentObjectRenderer->setRequest($GLOBALS['TYPO3_REQUEST']);
+$contentObjectRenderer->start([], 'pages');
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

### MigrateRequiredFlagSiteConfigRector

Migrate required flag

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MigrateRequiredFlagSiteConfigRector`](../rules/TYPO312/v0/MigrateRequiredFlagSiteConfigRector.php)

```diff
 $GLOBALS['SiteConfiguration']['site']['columns']['required_column1'] = [
     'required_column' => [
         'config' => [
-            'eval' => 'trim,required',
+            'eval' => 'trim',
+            'required' = true,
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

### MigrateTypoScriptFrontendControllerTypeRector

Migrate `TypoScriptFrontendController->type`

- class: [`Ssch\TYPO3Rector\TYPO312\v4\MigrateTypoScriptFrontendControllerTypeRector`](../rules/TYPO312/v4/MigrateTypoScriptFrontendControllerTypeRector.php)

```diff
-$GLOBALS['TSFE']->type;
+$GLOBALS['TSFE']->getPageArguments()->getPageType();
```

<br>

### MoveAllowTableOnStandardPagesToTCAConfigurationRector

Move method `ExtensionManagementUtility::allowTableOnStandardPages()` to TCA configuration

- class: [`Ssch\TYPO3Rector\TYPO312\v0\MoveAllowTableOnStandardPagesToTCAConfigurationRector`](../rules/TYPO312/v0/MoveAllowTableOnStandardPagesToTCAConfigurationRector.php)

```diff
-use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
-ExtensionManagementUtility::allowTableOnStandardPages('my_table');
+$GLOBALS['TCA']['my_table']['ctrl']['security']['ignorePageTypeRestriction'] = true;
```

<br>

### RemoveAddLLrefForTCAdescrMethodCallRector

Remove `ExtensionManagementUtility::addLLrefForTCAdescr()` method call

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveAddLLrefForTCAdescrMethodCallRector`](../rules/TYPO312/v0/RemoveAddLLrefForTCAdescrMethodCallRector.php)

```diff
-use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
-ExtensionManagementUtility::addLLrefForTCAdescr('_MOD_web_info', 'EXT:info/Resources/Private/Language/locallang_csh_web_info.xlf');
+use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
```

<br>

### RemoveCruserIdRector

Remove the TCA option "cruser_id"

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

### RemoveObsoleteAppearanceConfigRector

Removes the obsolete appearance config options within TCA

- class: [`Ssch\TYPO3Rector\TYPO312\v0\RemoveObsoleteAppearanceConfigRector`](../rules/TYPO312/v0/RemoveObsoleteAppearanceConfigRector.php)

```diff
 return [
     'columns' => [
         'random' => [
             'config' => [
                 'type' => 'group',
-                'appearance' => [
-                    'elementBrowserType' => 'db',
-                    'elementBrowserAllowed' => 'foo',
-                ],
             ],
         ],
         'random-inline' => [
             'config' => [
                 'type' => 'inline',
-                'appearance' => [
-                    'headerThumbnail' => 'db',
-                    'fileUploadAllowed' => 'foo',
-                    'fileByUrlAllowed' => 'foo',
-                ],
             ],
         ],
     ],
 ],
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

Remove `['interface']['always_description']`

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

Removes usages of `TSFE->convOutputCharset()`

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

Replace `$GLOBALS['TSFE']->checkEnableFields` calls with new `RecordAccessVoter->accessGranted()` method

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

### TemplateServiceToServerRequestFrontendTypoScriptAttributeRector

Migrate TemplateService to ServerRequest frontend.typsocript attribute

- class: [`Ssch\TYPO3Rector\TYPO312\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector`](../rules/TYPO312/v1/TemplateServiceToServerRequestFrontendTypoScriptAttributeRector.php)

```diff
-$setup = $GLOBALS['TSFE']->tmpl->setup;
+$setup = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();
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

### UseLanguageAspectInExtbasePersistenceRector

Use LanguageAspect in Extbase Persistence

- class: [`Ssch\TYPO3Rector\TYPO312\v0\UseLanguageAspectInExtbasePersistenceRector`](../rules/TYPO312/v0/UseLanguageAspectInExtbasePersistenceRector.php)

```diff
+use TYPO3\CMS\Core\Context\LanguageAspect;
+
 $query = $this->createQuery();
-$query->getQuerySettings()->setLanguageOverlayMode(false);
+$languageAspect = $query->getQuerySettings()->getLanguageAspect();
+$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_OFF);
+$query->getQuerySettings()->setLanguageAspect($languageAspect);
```

<br>

```diff
+use TYPO3\CMS\Core\Context\LanguageAspect;
+
 $query = $this->createQuery();
-$query->getQuerySettings()->setLanguageOverlayMode(true);
+$languageAspect = $query->getQuerySettings()->getLanguageAspect();
+$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_MIXED);
+$query->getQuerySettings()->setLanguageAspect($languageAspect);
```

<br>

```diff
+use TYPO3\CMS\Core\Context\LanguageAspect;
+
 $query = $this->createQuery();
-$query->getQuerySettings()->setLanguageOverlayMode('hideNonTranslated');
+$languageAspect = $query->getQuerySettings()->getLanguageAspect();
+$languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_ON);
+$query->getQuerySettings()->setLanguageAspect($languageAspect);
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

### UseServerRequestInsteadOfGeneralUtilityGetRector

Migrate `GeneralUtility::_GET()` to use PSR-7 ServerRequest instead

- class: [`Ssch\TYPO3Rector\TYPO312\v4\UseServerRequestInsteadOfGeneralUtilityGetRector`](../rules/TYPO312/v4/UseServerRequestInsteadOfGeneralUtilityGetRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-$value = GeneralUtility::_GET('tx_scheduler');
+$value = $GLOBALS['TYPO3_REQUEST']->getQueryParams()['tx_scheduler'] ?? null;
```

<br>

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod()
     {
-        $value = GeneralUtility::_GET('tx_scheduler');
+        $value = $this->request->getQueryParams()['tx_scheduler'] ?? null;
     }
 }
```

<br>

### UseServerRequestInsteadOfGeneralUtilityPostRector

Use PSR-7 ServerRequest instead of `GeneralUtility::_POST()`

- class: [`Ssch\TYPO3Rector\TYPO312\v0\UseServerRequestInsteadOfGeneralUtilityPostRector`](../rules/TYPO312/v0/UseServerRequestInsteadOfGeneralUtilityPostRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-$value = GeneralUtility::_POST('tx_scheduler');
+$value = $GLOBALS['TYPO3_REQUEST']->getParsedBody()['tx_scheduler'] ?? null;
```

<br>

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod()
     {
-        $value = GeneralUtility::_POST('tx_scheduler');
+        $value = $this->request->getParsedBody()['tx_scheduler'] ?? null;
     }
 }
```

<br>

## TYPO313

### AddMethodGetAllPageNumbersToPaginationInterfaceRector

Add new method getAllPageNumbers to classes implementing PaginationInterface

- class: [`Ssch\TYPO3Rector\TYPO313\v0\AddMethodGetAllPageNumbersToPaginationInterfaceRector`](../rules/TYPO313/v0/AddMethodGetAllPageNumbersToPaginationInterfaceRector.php)

```diff
 use TYPO3\CMS\Core\Pagination\PaginationInterface;

 class MySpecialPaginationImplementingPaginationInterface implements PaginationInterface
 {
+    /**
+     * @return int[]
+     */
+    public function getAllPageNumbers(): array
+    {
+        return range($this->getFirstPageNumber(), $this->getLastPageNumber());
+    }
 }
```

<br>

### ChangeSignatureForLastInsertIdRector

Remove table argument from `lastInsertID()` call

- class: [`Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureForLastInsertIdRector`](../rules/TYPO313/v0/ChangeSignatureForLastInsertIdRector.php)

```diff
 use TYPO3\CMS\Core\Database\ConnectionPool;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $connection = GeneralUtility::makeInstance(ConnectionPool::class)
     ->getConnectionForTable('tx_myextension_mytable');

-$uid = $connection->lastInsertId('tx_myextension_mytable');
+$uid = $connection->lastInsertId();
```

<br>

### ChangeSignatureOfConnectionQuoteRector

Ensure first parameter is of type string and remove second parameter

- class: [`Ssch\TYPO3Rector\TYPO313\v0\ChangeSignatureOfConnectionQuoteRector`](../rules/TYPO313/v0/ChangeSignatureOfConnectionQuoteRector.php)

```diff
 use TYPO3\CMS\Core\Database\Connection;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $connection = GeneralUtility::makeInstance(Connection::class);
-$connection->quote(1, 1);
+$connection->quote((string) 1);
```

<br>

### ConvertVersionStateToEnumRector

Convert usages of `TYPO3\CMS\Core\Versioning\VersionState` to its Enum equivalent

- class: [`Ssch\TYPO3Rector\TYPO313\v0\ConvertVersionStateToEnumRector`](../rules/TYPO313/v0/ConvertVersionStateToEnumRector.php)

```diff
 use TYPO3\CMS\Core\Versioning\VersionState;

 class MyClass
 {
     public function foo(): void
     {
-        $type1 = VersionState::DEFAULT_STATE;
-        $type2 = VersionState::NEW_PLACEHOLDER;
-        $type3 = VersionState::DELETE_PLACEHOLDER;
-        $type4 = VersionState::MOVE_POINTER;
+        $type1 = VersionState::DEFAULT_STATE->value;
+        $type2 = VersionState::NEW_PLACEHOLDER->value;
+        $type3 = VersionState::DELETE_PLACEHOLDER->value;
+        $type4 = VersionState::MOVE_POINTER->value;

-        $versionState = VersionState::cast($row['t3ver_state']);
-        if ($versionState->equals(VersionState::DELETE_PLACEHOLDER)) {
+        $versionState = VersionState::tryFrom($row['t3ver_state'] ?? 0);
+        if ($versionState === VersionState::DELETE_PLACEHOLDER) {
             // do something
         }
     }
 }
```

<br>

### EventListenerConfigurationToAttributeRector

Use AsEventListener attribute.

To run this rule, you need to do the following steps:
- Require `"ssch/typo3-debug-dump-pass": "^0.0.2"` in your composer.json
- Add `->withSymfonyContainerXml(__DIR__ . '/var/cache/development/App_KernelDevelopmentDebugContainer.xml')` in your rector config file.
- Clear the TYPO3 cache via cmd: `vendor/bin/typo3 cache:flush` to create the `App_KernelDevelopmentDebugContainer.xml` file.
- Finally run Rector.

- class: [`Ssch\TYPO3Rector\TYPO313\v0\EventListenerConfigurationToAttributeRector`](../rules/TYPO313/v0/EventListenerConfigurationToAttributeRector.php)

```diff
 namespace MyVendor\MyExtension\EventListener;

+use TYPO3\CMS\Core\Attribute\AsEventListener;
 use TYPO3\CMS\Core\Mail\Event\AfterMailerInitializationEvent;

+#[AsEventListener(
+    identifier: 'my-extension/null-mailer'
+)]
 final class NullMailer
 {
     public function __invoke(AfterMailerInitializationEvent $event): void
     {
     }
 }
```

<br>

### IntroduceCapabilitiesBitSetRector

Introduce capabilities bit set

- class: [`Ssch\TYPO3Rector\TYPO313\v0\IntroduceCapabilitiesBitSetRector`](../rules/TYPO313/v0/IntroduceCapabilitiesBitSetRector.php)

```diff
+use TYPO3\CMS\Core\Resource\Capabilities;
 use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

-echo ResourceStorageInterface::CAPABILITY_BROWSABLE;
-echo ResourceStorageInterface::CAPABILITY_PUBLIC;
-echo ResourceStorageInterface::CAPABILITY_WRITABLE;
-echo ResourceStorageInterface::CAPABILITY_HIERARCHICAL_IDENTIFIERS;
+echo Capabilities::CAPABILITY_BROWSABLE;
+echo Capabilities::CAPABILITY_PUBLIC;
+echo Capabilities::CAPABILITY_WRITABLE;
+echo Capabilities::CAPABILITY_HIERARCHICAL_IDENTIFIERS;
```

<br>

### MigrateAddPageTSConfigToPageTsConfigFileRector

Migrate method call `ExtensionManagementUtility::addPageTSConfig()` to page.tsconfig

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateAddPageTSConfigToPageTsConfigFileRector`](../rules/TYPO313/v0/MigrateAddPageTSConfigToPageTsConfigFileRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
-    '@import "EXT:extension_key/Configuration/TSconfig/*/*.tsconfig"'
-);
+// Move to file Configuration/page.tsconfig
```

<br>

### MigrateAddUserTSConfigToUserTsConfigFileRector

Migrate method call `ExtensionManagementUtility::addUserTSConfig()` to user.tsconfig

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateAddUserTSConfigToUserTsConfigFileRector`](../rules/TYPO313/v0/MigrateAddUserTSConfigToUserTsConfigFileRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
-    '@import "EXT:extension_key/Configuration/TSconfig/*/*.tsconfig"'
-);
+// Move to file Configuration/user.tsconfig
```

<br>

### MigrateBackendUtilityGetTcaFieldConfigurationRector

Migrate `BackendUtility::getTcaFieldConfiguration()`

- class: [`Ssch\TYPO3Rector\TYPO313\v3\MigrateBackendUtilityGetTcaFieldConfigurationRector`](../rules/TYPO313/v3/MigrateBackendUtilityGetTcaFieldConfigurationRector.php)

```diff
-$fieldConfig = \TYPO3\CMS\Backend\Utility\BackendUtility::getTcaFieldConfiguration('my_table', 'my_field');
+$fieldConfig = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Schema\TcaSchemaFactory::class)->get('my_table')->getField('my_field')->getConfiguration();
```

<br>

### MigrateDataProviderContextGettersAndSettersRector

Migrate DataProviderContext getters and setters

- class: [`Ssch\TYPO3Rector\TYPO313\v4\MigrateDataProviderContextGettersAndSettersRector`](../rules/TYPO313/v4/MigrateDataProviderContextGettersAndSettersRector.php)

```diff
-$dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
-$dataProviderContext
-    ->setPageId($pageId)
-    ->setTableName($parameters['table'])
-    ->setFieldName($parameters['field'])
-    ->setData($parameters['row'])
-    ->setPageTsConfig($pageTsConfig);
+$dataProviderContext = new DataProviderContext(
+    pageId: $pageId,
+    tableName: $parameters['table'],
+    fieldName: $parameters['field'],
+    data: $parameters['row'],
+    pageTsConfig: $pageTsConfig,
+);
```

<br>

```diff
-$pageId = $dataProviderContext->getPageId();
-$tableName = $dataProviderContext->getTableName();
-$fieldName = $dataProviderContext->getFieldName();
-$data = $dataProviderContext->getData();
-$pageTsConfig = $dataProviderContext->getPageTsConfig();
+$pageId = $dataProviderContext->pageId;
+$tableName = $dataProviderContext->tableName;
+$fieldName = $dataProviderContext->fieldName;
+$data = $dataProviderContext->data;
+$pageTsConfig = $dataProviderContext->pageTsConfig;
```

<br>

```diff
-$dataProviderContext->setPageId(1);
-$dataProviderContext->setTableName('table');
-$dataProviderContext->setFieldName('field');
-$dataProviderContext->setData([]);
-$dataProviderContext->setPageTsConfig([]);
+$dataProviderContext->pageId = 1;
+$dataProviderContext->tableName = 'table';
+$dataProviderContext->fieldName = 'field';
+$dataProviderContext->data = [];
+$dataProviderContext->pageTsConfig = [];
```

<br>

### MigrateDuplicationBehaviorClassRector

Convert usages of DuplicationBehavior to its Enum equivalent

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateDuplicationBehaviorClassRector`](../rules/TYPO313/v0/MigrateDuplicationBehaviorClassRector.php)

```diff
-$file->copyTo($folder, null, \TYPO3\CMS\Core\Resource\DuplicationBehavior::REPLACE);
+$file->copyTo($folder, null, \TYPO3\CMS\Core\Resource\Enum\DuplicationBehavior::REPLACE);
```

<br>

### MigrateExpressionBuilderTrimMethodSecondParameterRector

Migrate second parameter of trim method to enum

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateExpressionBuilderTrimMethodSecondParameterRector`](../rules/TYPO313/v0/MigrateExpressionBuilderTrimMethodSecondParameterRector.php)

```diff
 $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tt_content');
 $queryBuilder->expr()->comparison(
-    $queryBuilder->expr()->trim($fieldName, 1),
+    $queryBuilder->expr()->trim($fieldName, TrimMode::LEADING),
     ExpressionBuilder::EQ,
     $queryBuilder->createNamedParameter('', Connection::PARAM_STR)
 );
```

<br>

### MigrateExtbaseHashServiceToUseCoreHashServiceRector

Migrate the class HashService from extbase to the one from TYPO3 core

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateExtbaseHashServiceToUseCoreHashServiceRector`](../rules/TYPO313/v0/MigrateExtbaseHashServiceToUseCoreHashServiceRector.php)

```diff
+use TYPO3\CMS\Core\Crypto\HashService;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

 $hashService = GeneralUtility::makeInstance(HashService::class);

-$generatedHash = $hashService->generateHmac('123');
-$isValidHash = $hashService->validateHmac('123', $generatedHash);
+$generatedHash = $hashService->hmac('123', 'changeMe');
+$isValidHash = $hashService->validateHmac('123', 'changeMe', $generatedHash);

-$stringWithAppendedHash = $hashService->appendHmac('123');
-$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash);
+$stringWithAppendedHash = $hashService->appendHmac('123', 'changeMe');
+$validatedStringWithHashRemoved = $hashService->validateAndStripHmac($stringWithAppendedHash, 'changeMe');
```

<br>

### MigrateFluidStandaloneMethodsRector

Migrate Fluid standalone methods

- class: [`Ssch\TYPO3Rector\TYPO313\v3\MigrateFluidStandaloneMethodsRector`](../rules/TYPO313/v3/MigrateFluidStandaloneMethodsRector.php)

```diff
 public function initializeArguments(): void
 {
     parent::initializeArguments();
-    $this->registerUniversalTagAttributes();
 }
```

<br>

```diff
-if (empty($this->arguments['title']) && $title) {
+if (empty($this->additionalArguments['title']) && $title) {
     $this->tag->addAttribute('title', $title);
 }
```

<br>

### MigrateGeneralUtilityHmacToHashServiceHmacRector

Migrate `GeneralUtility::hmac()` to `HashService::hmac()`

- class: [`Ssch\TYPO3Rector\TYPO313\v1\MigrateGeneralUtilityHmacToHashServiceHmacRector`](../rules/TYPO313/v1/MigrateGeneralUtilityHmacToHashServiceHmacRector.php)

```diff
+use TYPO3\CMS\Core\Crypto\HashService;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-$hmac = GeneralUtility::hmac('some-input', 'some-secret');
+$hmac = GeneralUtility::makeInstance(HashService::class)->hmac('some-input', 'some-secret');
```

<br>

### MigrateLegacySettingGFXgdlibRector

Migrate legacy setting `GFX/gdlib`

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateLegacySettingGFXgdlibRector`](../rules/TYPO313/v0/MigrateLegacySettingGFXgdlibRector.php)

```diff
-if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] === true) {
+if (class_exists(\GdImage::class)) {
     // do something
 }
```

<br>

### MigrateMathUtilityConvertToPositiveIntegerToMaxRector

Migrate `MathUtility::convertToPositiveInteger()` to `max()`

- class: [`Ssch\TYPO3Rector\TYPO313\v2\MigrateMathUtilityConvertToPositiveIntegerToMaxRector`](../rules/TYPO313/v2/MigrateMathUtilityConvertToPositiveIntegerToMaxRector.php)

```diff
-MathUtility::convertToPositiveInteger($pageId)
+max(0, $pageId)
```

<br>

### MigrateNamespacedShortHandValidatorRector

Migrate namespaced shorthand validator usage in Extbase

- class: [`Ssch\TYPO3Rector\TYPO313\v2\MigrateNamespacedShortHandValidatorRector`](../rules/TYPO313/v2/MigrateNamespacedShortHandValidatorRector.php)

```diff
 /**
- * @Extbase\Validate("TYPO3.CMS.Extbase:NotEmpty")
+ * @Extbase\Validate("NotEmpty")
  */
 protected $myProperty1;

 /**
- * @Extbase\Validate("Vendor.Extension:Custom")
+ * @Extbase\Validate("Vendor\Extension\Validation\Validator\CustomValidator")
  */
 protected $myProperty2;
```

<br>

### MigratePluginContentElementAndPluginSubtypesRector

Migrate plugin content element and plugin subtypes (list_type)

- class: [`Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesRector`](../rules/TYPO313/v4/MigratePluginContentElementAndPluginSubtypesRector.php)

```diff
-\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin([], 'list_type', 'extension_key');
-\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'list_type');
+\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin([], 'CType', 'extension_key');
+\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'CType');
```

<br>

### MigratePluginContentElementAndPluginSubtypesSwapArgsRector

Swap arguments for `ExtensionManagementUtility::addPiFlexFormValue()`

- class: [`Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesSwapArgsRector`](../rules/TYPO313/v4/MigratePluginContentElementAndPluginSubtypesSwapArgsRector.php)

```diff
 ExtensionManagementUtility::addPiFlexFormValue(
+    '*',
+    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
     $pluginSignature,
-    'FILE:EXT:examples/Configuration/Flexforms/HtmlParser.xml',
 );
```

<br>

### MigratePluginContentElementAndPluginSubtypesTCARector

Migrate plugin content element and plugin subtypes (list_type) TCA

- class: [`Ssch\TYPO3Rector\TYPO313\v4\MigratePluginContentElementAndPluginSubtypesTCARector`](../rules/TYPO313/v4/MigratePluginContentElementAndPluginSubtypesTCARector.php)

```diff
-$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
+ExtensionManagementUtility::addToAllTCAtypes(
+    'tt_content',
+    '--div--;Configuration,pi_flexform,',
+    $pluginSignature,
+    'after:subheader',
+);
```

<br>

### MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector

Migrate RegularExpressionValidator validator option "errorMessage"

- class: [`Ssch\TYPO3Rector\TYPO313\v2\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector`](../rules/TYPO313/v2/MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector.php)

```diff
 use TYPO3\CMS\Extbase\Annotation as Extbase;

 #[Extbase\Validate([
     'validator' => 'RegularExpression',
     'options' => [
         'regularExpression' => '/^simple[0-9]expression$/',
-        'errorMessage' => 'Error message or LLL schema string',
+        'message' => 'Error message or LLL schema string'
     ],
 ])]
 protected string $myProperty = '';
```

<br>

### MigrateTableDependentDefinitionOfColumnsOnlyRector

Migrate table dependant definition of columnsOnly

- class: [`Ssch\TYPO3Rector\TYPO313\v2\MigrateTableDependentDefinitionOfColumnsOnlyRector`](../rules/TYPO313/v2/MigrateTableDependentDefinitionOfColumnsOnlyRector.php)

```diff
 $urlParameters = [
     'edit' => [
         'pages' => [
             1 => 'edit',
         ],
     ],
-    'columnsOnly' => 'title,slug'
+    'columnsOnly' => [
+        'pages' => [
+            'title',
+            'slug'
+        ]
+    ],
     'returnUrl' => $request->getAttribute('normalizedParams')->getRequestUri(),
 ];

 GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit', $urlParameters);
```

<br>

### MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector

Migrate `TypoScriptFrontendController->addCacheTags()` and `->getPageCacheTags()`

- class: [`Ssch\TYPO3Rector\TYPO313\v3\MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector`](../rules/TYPO313/v3/MigrateTypoScriptFrontendControllerAddCacheTagsAndGetPageCacheTagsRector.php)

```diff
-$GLOBALS['TSFE']->addCacheTags([
-    'tx_myextension_mytable_123',
-    'tx_myextension_mytable_456'
-]);
+use TYPO3\CMS\Core\Cache\CacheTag;
+
+$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->addCacheTags(
+    new CacheTag('tx_myextension_mytable_123', 3600),
+    new CacheTag('tx_myextension_mytable_456', 3600)
+);
```

<br>

```diff
-$tags = $GLOBALS['TSFE']->getPageCacheTags();
+$tags = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags();
```

<br>

### MigrateTypoScriptFrontendControllerFeUserMethodsRector

Migrate `$GLOBALS['TSFE']->fe_user->xxx()` methods to use the request attribute

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerFeUserMethodsRector.php)

```diff
-$GLOBALS['TSFE']->fe_user->setKey('ses', 'extension', 'value');
-$GLOBALS['TSFE']->fe_user->getKey('ses', 'extension');
+$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
+$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getKey('ses', 'extension');
```

<br>

### MigrateTypoScriptFrontendControllerFeUserRector

Migrate `$GLOBALS['TSFE']->fe_user` to use the request attribute

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerFeUserRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerFeUserRector.php)

```diff
-$frontendUser = $GLOBALS['TSFE']->fe_user;
+$frontendUser = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user');

-$GLOBALS['TSFE']->fe_user->setKey('ses', 'key', 'value');
+$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'key', 'value');

-if (is_array($GLOBALS['TSFE']->fe_user->user) && $GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
-    $id = $GLOBALS['TSFE']->fe_user->user['uid'];
+if (is_array($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user) && $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'] > 0) {
+    $id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'];
 }
```

<br>

```diff
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyActionController extends ActionController
 {
     public function myMethod(): void
     {
-        $frontendUser = $GLOBALS['TSFE']->fe_user;
+        $frontendUser = $this->request->getAttribute('frontend.user');

-        if (is_array($GLOBALS['TSFE']->fe_user->user) && $GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
-            $id = $GLOBALS['TSFE']->fe_user->user['uid'];
+        if (is_array($this->request->getAttribute('frontend.user')->user) && $this->request->getAttribute('frontend.user')->user['uid'] > 0) {
+            $id = $this->request->getAttribute('frontend.user')->user['uid'];
         }
     }
 }
```

<br>

### MigrateTypoScriptFrontendControllerGetContextRector

Migrate `$GLOBALS['TSFE']->getContext()`

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerGetContextRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerGetContextRector.php)

```diff
-$context = $GLOBALS['TSFE']->getContext();
+$context = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class);
```

<br>

### MigrateTypoScriptFrontendControllerMethodCallsRector

Migrate TypoScriptFrontendController method calls and use the request attribute

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerMethodCallsRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerMethodCallsRector.php)

```diff
-$GLOBALS['TSFE']->getRequestedId();
+$GLOBALS['TYPO3_REQUEST']->getAttribute('routing')->getPageId();
```

<br>

```diff
-$GLOBALS['TSFE']->getLanguage();
+$GLOBALS['TYPO3_REQUEST']->getAttribute('language') ?? $GLOBALS['TYPO3_REQUEST']->getAttribute('site')->getDefaultLanguage();
```

<br>

```diff
-$GLOBALS['TSFE']->getSite();
+$GLOBALS['TYPO3_REQUEST']->getAttribute('site');
```

<br>

```diff
-$GLOBALS['TSFE']->getPageArguments();
+$GLOBALS['TYPO3_REQUEST']->getAttribute('routing');
```

<br>

### MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector

Migrate TypoScriptFrontendController readonly properties

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerReadOnlyPropertiesRector.php)

```diff
-$id = $GLOBALS['TSFE']->id;
-$rootLine = $GLOBALS['TSFE']->rootLine;
-$page = $GLOBALS['TSFE']->page;
-$contentPid = $GLOBALS['TSFE']->contentPid;
+$id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getId();
+$rootLine = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getRootLine();
+$page = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();
+$contentPid = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getContentFromPid();
```

<br>

### MigrateTypoScriptFrontendControllerSysPageRector

Migrate `TypoScriptFrontendController->sys_page`

- class: [`Ssch\TYPO3Rector\TYPO313\v0\MigrateTypoScriptFrontendControllerSysPageRector`](../rules/TYPO313/v0/MigrateTypoScriptFrontendControllerSysPageRector.php)

```diff
-$sys_page = $GLOBALS['TSFE']->sys_page;
-$GLOBALS['TSFE']->sys_page->enableFields('table');
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Domain\Repository\PageRepository;
+
+$sys_page = GeneralUtility::makeInstance(PageRepository::class);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('table');
```

<br>

### MigrateViewHelperRenderStaticRector

Migrate static ViewHelpers to object-based ViewHelpers

- class: [`Ssch\TYPO3Rector\TYPO313\v3\MigrateViewHelperRenderStaticRector`](../rules/TYPO313/v3/MigrateViewHelperRenderStaticRector.php)

```diff
 class MyViewHelper extends AbstractViewHelper
 {
-    use CompileWithRenderStatic;
-
-    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
+    public function render(): string
     {
-        return $renderChildrenClosure();
+        return $this->renderChildren();
     }
 }
```

<br>

### RemoveAddRootLineFieldsRector

Remove obsolete `$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields']`

- class: [`Ssch\TYPO3Rector\TYPO313\v2\RemoveAddRootLineFieldsRector`](../rules/TYPO313/v2/RemoveAddRootLineFieldsRector.php)

```diff
-$GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = 'foo';
+-
```

<br>

### RemoveConstantPageRepositoryDoktypeRecyclerRector

Remove the constant `TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_RECYCLER` and its usage in arrays and binary operations (||, &&)

- class: [`Ssch\TYPO3Rector\TYPO313\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector`](../rules/TYPO313/v0/RemoveConstantPageRepositoryDoktypeRecyclerRector.php)

```diff
 $excludeDoktypes = [
-    \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_RECYCLER,
     \TYPO3\CMS\Core\Domain\Repository\PageRepository::DOKTYPE_SYSFOLDER,
 ];
```

<br>

### RemoveMmHasUidFieldRector

Unset the value in the config mmHasUidField

- class: [`Ssch\TYPO3Rector\TYPO313\v0\RemoveMmHasUidFieldRector`](../rules/TYPO313/v0/RemoveMmHasUidFieldRector.php)

```diff
 return [
     'columns' => [
         'nullable_column' => [
             'config' => [
                 'type' => 'group',
-                'MM_hasUidField' => false,
             ],
         ],
     ],
 ];
```

<br>

### RemoveSpecialPropertiesOfPageArraysRector

Remove special properties of page array in page repository

- class: [`Ssch\TYPO3Rector\TYPO313\v0\RemoveSpecialPropertiesOfPageArraysRector`](../rules/TYPO313/v0/RemoveSpecialPropertiesOfPageArraysRector.php)

```diff
-$rows['_PAGES_OVERLAY_UID']
+$rows['_LOCALIZED_UID']
```

<br>

```diff
-$rows['_PAGES_OVERLAY_REQUESTEDLANGUAGE']
+$rows['_REQUESTED_OVERLAY_LANGUAGE']
```

<br>

### RemoveTcaSubTypesExcludeListTCARector

Remove subtypes_excludelist from list type

- class: [`Ssch\TYPO3Rector\TYPO313\v4\RemoveTcaSubTypesExcludeListTCARector`](../rules/TYPO313/v4/RemoveTcaSubTypesExcludeListTCARector.php)

```diff
-$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['my_plugin'] = 'layout,select_key,pages';
+-
```

<br>

### RenamePageTreeNavigationComponentIdRector

Renamed Page Tree Navigation Component ID

- class: [`Ssch\TYPO3Rector\TYPO313\v1\RenamePageTreeNavigationComponentIdRector`](../rules/TYPO313/v1/RenamePageTreeNavigationComponentIdRector.php)

```diff
 return [
     'mymodule' => [
         'parent' => 'web',
-        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
+        'navigationComponent' => '@typo3/backend/tree/page-tree-element',
     ],
 ];
```

<br>

### RenameTableOptionsAndCollateConnectionConfigurationRector

Rename `$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections'][CONNECTION_NAME]['tableoptions']` to `defaultTableOptions` and its inner `collate` key to `collation`

- class: [`Ssch\TYPO3Rector\TYPO313\v4\RenameTableOptionsAndCollateConnectionConfigurationRector`](../rules/TYPO313/v4/RenameTableOptionsAndCollateConnectionConfigurationRector.php)

```diff
 return [
     'DB' => [
         'Connections' => [
             'Default' => [
-                'tableoptions' => [
-                    'collate' => 'utf8mb4_unicode_ci',
+                'defaultTableOptions' => [
+                    'collation' => 'utf8mb4_unicode_ci',
                 ],
             ],
         ],
     ],
 ];
```

<br>

```diff
-$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions']['collate'] = 'utf8mb4_unicode_ci';
+$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions']['collation'] = 'utf8mb4_unicode_ci';
```

<br>

```diff
-$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['tableoptions'] = [
-    'collate' => 'latin1_swedish_ci',
+$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['defaultTableOptions'] = [
+    'collation' => 'latin1_swedish_ci',
     'engine' => 'InnoDB',
 ];
```

<br>

### ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector

Replace TYPO3 EnumType with Doctrine DBAL EnumType

- class: [`Ssch\TYPO3Rector\TYPO313\v4\ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector`](../rules/TYPO313/v4/ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector.php)

```diff
-$doctrineType = \TYPO3\CMS\Core\Database\Schema\Types\EnumType::TYPE;
+$doctrineType = \Doctrine\DBAL\Types\Type::TYPE;
```

<br>

### RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector

Require a template filename in extbase module template rendering

- class: [`Ssch\TYPO3Rector\TYPO313\v4\RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector`](../rules/TYPO313/v4/RequireATemplateFileNameInExtbaseModuleTemplateRenderingRector.php)

```diff
-$moduleTemplate->renderResponse();
+$moduleTemplate->renderResponse('MyController/MyAction');
```

<br>

### StrictTypesPersistenceManagerRector

Strict types for PersistenceManager

- class: [`Ssch\TYPO3Rector\TYPO313\v0\StrictTypesPersistenceManagerRector`](../rules/TYPO313/v0/StrictTypesPersistenceManagerRector.php)

```diff
-protected $newObjects = [];
-protected $changedObjects;
-protected $addedObjects;
-protected $removedObjects;
-protected $queryFactory;
-protected $backend;
-protected $persistenceSession;
+protected array $newObjects = [];
+protected ObjectStorage $changedObjects;
+protected ObjectStorage $addedObjects;
+protected ObjectStorage $removedObjects;
+protected QueryFactoryInterface $queryFactory;
+protected BackendInterface $backend;
+protected Session $persistenceSession;
```

<br>

### SubstituteItemFormElIDRector

Substitute itemFormElID key with custom generator

- class: [`Ssch\TYPO3Rector\TYPO313\v0\SubstituteItemFormElIDRector`](../rules/TYPO313/v0/SubstituteItemFormElIDRector.php)

```diff
-$attributeId = htmlspecialchars($this->data['parameterArray']['itemFormElID']);
+$attributeId = htmlspecialchars(StringUtility::getUniqueId(self::class . '-'));
 $html[] = '<input id="' . $attributeId . '">';
```

<br>

### TcaDefaultsRector

Add a default value to TCA fields if missing

- class: [`Ssch\TYPO3Rector\TYPO313\v4\TcaDefaultsRector`](../rules/TYPO313/v4/TcaDefaultsRector.php)

```diff
 return [
     'columns' => [
         'nullable_column' => [
             'config' => [
                 'type' => 'input',
+                'default' => '',
             ],
         ],
     ],
 ];
```

<br>

### UseStrictTypesInExtbaseAbstractDomainObjectRector

Use strict types in Extbase AbstractDomainObject

- class: [`Ssch\TYPO3Rector\TYPO313\v0\UseStrictTypesInExtbaseAbstractDomainObjectRector`](../rules/TYPO313/v0/UseStrictTypesInExtbaseAbstractDomainObjectRector.php)

```diff
 abstract class AbstractDomainObject
 {
-    protected $uid;
-    protected $pid;
+    protected ?int $uid = null;
+    protected ?int $pid = null;
 }
```

<br>

### UseStrictTypesInExtbaseActionControllerRector

Use strict types in Extbase ActionController

- class: [`Ssch\TYPO3Rector\TYPO313\v0\UseStrictTypesInExtbaseActionControllerRector`](../rules/TYPO313/v0/UseStrictTypesInExtbaseActionControllerRector.php)

```diff
 namespace Vendor\MyExtension\Controller;

 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyController extends ActionController
 {
-    public $defaultViewObjectName = JsonView::class;
-    public $errorMethodName = 'myAction';
+    public string $defaultViewObjectName = JsonView::class;
+    public string $errorMethodName = 'myAction';
 }
```

<br>

### UseTYPO3CoreViewInterfaceInExtbaseRector

Use `\TYPO3\CMS\Core\View\ViewInterface` in Extbase and call `$view->getRenderingContext()` to perform operations instead

- class: [`Ssch\TYPO3Rector\TYPO313\v3\UseTYPO3CoreViewInterfaceInExtbaseRector`](../rules/TYPO313/v3/UseTYPO3CoreViewInterfaceInExtbaseRector.php)

```diff
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

 class MyController extends ActionController
 {
     public function myAction()
     {
         // setTemplate
-        $this->view->setTemplate('MyTemplate');
+        $this->view->getRenderingContext()->setControllerAction('MyTemplate');

         // initializeRenderingContext
-        $this->view->initializeRenderingContext();
+        $this->view->getRenderingContext()->getViewHelperVariableContainer()->setView($this->view);

         // setCache
         $cache = new SimpleFileCache();
-        $this->view->setCache($cache);
+        $this->view->getRenderingContext()->setCache($cache);

         // getTemplatePaths
-        $templatePaths = $this->view->getTemplatePaths();
+        $templatePaths = $this->view->getRenderingContext()->getTemplatePaths();

         // getViewHelperResolver
-        $viewHelperResolver = $this->view->getViewHelperResolver();
+        $viewHelperResolver = $this->view->getRenderingContext()->getViewHelperResolver();

         // setTemplatePathAndFilename
-        $this->view->setTemplatePathAndFilename('path/to/template.html');
+        $this->view->getRenderingContext()->getTemplatePaths()->setTemplatePathAndFilename('path/to/template.html');

         // setTemplateRootPaths
-        $this->view->setTemplateRootPaths(['path/to/templates/']);
+        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateRootPaths(['path/to/templates/']);

         // getTemplateRootPaths
-        $rootPaths = $this->view->getTemplateRootPaths();
+        $rootPaths = $this->view->getRenderingContext()->getTemplatePaths()->getTemplateRootPaths();

         // setPartialRootPaths
-        $this->view->setPartialRootPaths(['path/to/partials/']);
+        $this->view->getRenderingContext()->getTemplatePaths()->setPartialRootPaths(['path/to/partials/']);

         // getPartialRootPaths
-        $partialPaths = $this->view->getPartialRootPaths();
+        $partialPaths = $this->view->getRenderingContext()->getTemplatePaths()->getPartialRootPaths();

         // getLayoutRootPaths
-        $layoutPaths = $this->view->getLayoutRootPaths();
+        $layoutPaths = $this->view->getRenderingContext()->getTemplatePaths()->getLayoutRootPaths();

         // setLayoutRootPaths
-        $this->view->setLayoutRootPaths(['path/to/layouts/']);
+        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutRootPaths(['path/to/layouts/']);

         // setLayoutPathAndFilename
-        $this->view->setLayoutPathAndFilename('path/to/layout.html');
+        $this->view->getRenderingContext()->getTemplatePaths()->setLayoutPathAndFilename('path/to/layout.html');

         // setRequest
-        $this->view->setRequest($this->request);
+        $this->view->getRenderingContext()->setAttribute(ServerRequestInterface::class, $this->request);

         // setTemplateSource
-        $this->view->setTemplateSource('<f:render section="Main" />');
+        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<f:render section="Main" />');
     }
 }
```

<br>

## TYPO314

### DropFifthParameterForExtensionUtilityConfigurePluginRector

Drop the fifth parameter `$pluginType` of `ExtensionUtility::configurePlugin()`

- class: [`Ssch\TYPO3Rector\TYPO314\v0\DropFifthParameterForExtensionUtilityConfigurePluginRector`](../rules/TYPO314/v0/DropFifthParameterForExtensionUtilityConfigurePluginRector.php)

```diff
-ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'CType');
+ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
```

<br>

```diff
-ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
+ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], []);
```

<br>

### ExtendExtbaseValidatorsFromAbstractValidatorRector

Extend Extbase Validators from AbstractValidator

- class: [`Ssch\TYPO3Rector\TYPO314\v0\ExtendExtbaseValidatorsFromAbstractValidatorRector`](../rules/TYPO314/v0/ExtendExtbaseValidatorsFromAbstractValidatorRector.php)

```diff
-class MyValidator implements ValidatorInterface
+class MyValidator extends AbstractValidator
 {
 }
```

<br>

### MigrateAdminPanelDataProviderInterfaceRector

Migrate Adminpanel DataProviderInterface

- class: [`Ssch\TYPO3Rector\TYPO314\v0\MigrateAdminPanelDataProviderInterfaceRector`](../rules/TYPO314/v0/MigrateAdminPanelDataProviderInterfaceRector.php)

```diff
-public function getDataToStore(\Psr\Http\Message\ServerRequestInterface $request): ModuleData;
+public function getDataToStore(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Message\ResponseInterface $response): ModuleData;
```

<br>

### MigrateBooleanSortDirectionInFileListRector

Migrate boolean sort direction in `\TYPO3\CMS\Filelist\FileList->start()`

- class: [`Ssch\TYPO3Rector\TYPO314\v0\MigrateBooleanSortDirectionInFileListRector`](../rules/TYPO314/v0/MigrateBooleanSortDirectionInFileListRector.php)

```diff
-$fileList->start($folder, $currentPage, $sortField, false, $mode);
-$fileList->start($folder, $currentPage, $sortField, true, $mode);
+$fileList->start($folder, $currentPage, $sortField, \TYPO3\CMS\Filelist\Type\SortDirection::ASCENDING, $mode);
+$fileList->start($folder, $currentPage, $sortField, \TYPO3\CMS\Filelist\Type\SortDirection::DESCENDING, $mode);
```

<br>

### MigrateEnvironmentGetComposerRootPathRector

Migrate `Environment::getComposerRootPath()` to `Environment::getProjectPath()`

- class: [`Ssch\TYPO3Rector\TYPO314\v0\MigrateEnvironmentGetComposerRootPathRector`](../rules/TYPO314/v0/MigrateEnvironmentGetComposerRootPathRector.php)

```diff
-\TYPO3\CMS\Core\Core\Environment::getComposerRootPath();
+\TYPO3\CMS\Core\Core\Environment::getProjectPath();
```

<br>

### MigrateIpAnonymizationTaskRector

Migrates the IpAnonymizationTask configuration from `$GLOBALS['TYPO3_CONF_VARS']` to `$GLOBALS['TCA'].`

- class: [`Ssch\TYPO3Rector\TYPO314\v0\MigrateIpAnonymizationTaskRector`](../rules/TYPO314/v0/MigrateIpAnonymizationTaskRector.php)

```diff
-$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]['options']['tables'] = [
-    'my_table' => [
-        'dateField' => 'tstamp',
-        'ipField' => 'private_ip',
-    ],
-];
+// Added under Configuration/TCA/Overrides/tx_scheduler_task.php
+if (isset($GLOBALS['TCA']['tx_scheduler_task'])) {
+    $GLOBALS['TCA']['tx_scheduler_task']['types'][\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]['taskOptions']['tables'] = [
+        'my_table' => [
+            'dateField' => 'tstamp',
+            'ipField' => 'private_ip',
+        ],
+    ];
+}
```

<br>

### MigrateObsoleteCharsetInSanitizeFileNameRector

Remove the second charset parameter from sanitizeFileName method in DriverInterface implementations

- class: [`Ssch\TYPO3Rector\TYPO314\v0\MigrateObsoleteCharsetInSanitizeFileNameRector`](../rules/TYPO314/v0/MigrateObsoleteCharsetInSanitizeFileNameRector.php)

```diff
 use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

 class MyDriver implements DriverInterface
 {
-    public function sanitizeFileName(string $fileName, string $charset = ''): string
+    public function sanitizeFileName(string $fileName): string
     {
     }
 }

 class SomeClass
 {
     public function doSomething(DriverInterface $driver)
     {
-        $sanitizedName = $driver->sanitizeFileName('example.txt', 'utf-8');
+        $sanitizedName = $driver->sanitizeFileName('example.txt');
     }
 }
```

<br>

### RemoveEvalYearFlagRector

Remove eval year flag

- class: [`Ssch\TYPO3Rector\TYPO314\v0\RemoveEvalYearFlagRector`](../rules/TYPO314/v0/RemoveEvalYearFlagRector.php)

```diff
 return [
     'columns' => [
         'year_column' => [
             'config' => [
-                'eval' => 'trim,year',
+                'eval' => 'trim',
             ],
         ],
     ],
 ];
```

<br>

### RemoveFieldSearchConfigOptionsRector

Remove TCA search field configuration options

- class: [`Ssch\TYPO3Rector\TYPO314\v0\RemoveFieldSearchConfigOptionsRector`](../rules/TYPO314/v0/RemoveFieldSearchConfigOptionsRector.php)

```diff
 return [
     'columns' => [
         'my_field' => [
             'config' => [
                 'type' => 'input',
-                'search' => [
-                    'case' => true,
-                    'pidonly' => true,
-                    'andWhere' => '{#CType}=\'text\'',
-                ],
             ],
         ],
     ],
 ];
```

<br>

### RemoveIsStaticControlOptionRector

Remove TCA control option is_static

- class: [`Ssch\TYPO3Rector\TYPO314\v0\RemoveIsStaticControlOptionRector`](../rules/TYPO314/v0/RemoveIsStaticControlOptionRector.php)

```diff
 return [
     'ctrl' => [
         'title' => 'foobar',
-        'is_static' => 'foo',
     ],
     'columns' => [
     ],
 ];
```

<br>

### RemoveMaxDBListItemsRector

Remove `$TCA[$mytable]['interface']['maxDBListItems']`, and 'maxSingleDBListItems'

- class: [`Ssch\TYPO3Rector\TYPO314\v0\RemoveMaxDBListItemsRector`](../rules/TYPO314/v0/RemoveMaxDBListItemsRector.php)

```diff
 return [
     'columns' => [],
-    'interface' => [
-        'maxDBListItems' => 'foo',
-        'maxSingleDBListItems' => 'foo',
-    ],
 ];
```

<br>

### RemoveParameterInAuthenticationServiceRector

Remove second argument `$passwordTransmissionStrategy` from `AuthenticationService->processLoginData()`

- class: [`Ssch\TYPO3Rector\TYPO314\v0\RemoveParameterInAuthenticationServiceRector`](../rules/TYPO314/v0/RemoveParameterInAuthenticationServiceRector.php)

```diff
-AuthenticationService->processLoginData($processedLoginData, 'normal');
+AuthenticationService->processLoginData($processedLoginData);
```

<br>

### UseRecordApiInListModuleRector

Use Record API in List Module

- class: [`Ssch\TYPO3Rector\TYPO314\v0\UseRecordApiInListModuleRector`](../rules/TYPO314/v0/UseRecordApiInListModuleRector.php)

```diff
-$this->renderListRow($table, $rowArray, $indent, $translations, $enabled);
-$this->makeControl($table, $row);
-$this->makeCheckbox($table, $row);
-$this->languageFlag($table, $row);
-$this->makeLocalizationPanel($table, $row);
-$this->linkWrapItems($table, 2, 'code', $row);
-$this->getPreviewUriBuilder($table, $row);
-$this->isRecordDeletePlaceholder($row);
-$this->isRowListingConditionFulfilled($table, $row);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $rowArray);
+$this->renderListRow($table, $record, $indent, $translations, $enabled);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->makeControl($table, $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->makeCheckbox($table, $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->languageFlag($table, $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->makeLocalizationPanel($table, $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->linkWrapItems($table, 2, 'code', $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->getPreviewUriBuilder($table, $record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->isRecordDeletePlaceholder($record);
+$record = $this->recordFactory->createResolvedRecordFromDatabaseRow($table, $row);
+$this->isRowListingConditionFulfilled($record);
```

<br>

## TypeDeclaration

### AddPropertyTypeDeclarationWithDefaultNullRector

Add type to property by added rules, mostly public/property by parent type with default value null

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\TypeDeclaration\Property\AddPropertyTypeDeclarationWithDefaultNullRector`](../rules/TypeDeclaration/Property/AddPropertyTypeDeclarationWithDefaultNullRector.php)

```diff
 class SomeClass extends ParentClass
 {
-    public $name;
+    public ?string $name = null;
 }
```

<br>
