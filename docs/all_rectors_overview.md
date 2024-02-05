# 303 Rules Overview

## AbstractMessageGetSeverityFluidRector

Migrate to severity property 'value'

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v12\v0\AbstractMessageGetSeverityFluidRector`](../src/FileProcessor/Fluid/Rector/v12/v0/AbstractMessageGetSeverityFluidRector.php)

```diff
-<div class="{severityClassMapping.{status.severity}}">
+<div class="{severityClassMapping.{status.severity.value}}">
     <!-- stuff happens here -->
 </div>
```

<br>

## AbstractMessageGetSeverityRector

Use value property on `getSeverity()`

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector`](../src/Rector/v12/v0/typo3/AbstractMessageGetSeverityRector.php)

```diff
 use \TYPO3\CMS\Core\Messaging\FlashMessage;

 $flashMessage = new FlashMessage('This is a message');
-$severityAsInt = $flashMessage->getSeverity();
+$severityAsInt = $flashMessage->getSeverity()->value;
```

<br>

## AddMethodToWidgetInterfaceClassesRector

Add `getOptions()` to classes that implement the WidgetInterface

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\AddMethodToWidgetInterfaceClassesRector`](../src/Rector/v12/v0/typo3/AddMethodToWidgetInterfaceClassesRector.php)

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

## AddRenderTypeToSelectFieldRector

Add renderType for select fields

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\AddRenderTypeToSelectFieldRector`](../src/Rector/v7/v6/AddRenderTypeToSelectFieldRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'sys_language_uid' => [
             'config' => [
                 'type' => 'select',
                 'maxitems' => 1,
+                'renderType' => 'selectSingle',
             ],
         ],
     ],
 ];
```

<br>

## AddSetConfigurationMethodToExceptionHandlerRector

Add method setConfiguration to class which implements ExceptionHandlerInterface

- class: [`Ssch\TYPO3Rector\Rector\v11\v4\AddSetConfigurationMethodToExceptionHandlerRector`](../src/Rector/v11/v4/AddSetConfigurationMethodToExceptionHandlerRector.php)

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

## AddTypeToColumnConfigRector

Add type to column config if not exists

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\AddTypeToColumnConfigRector`](../src/Rector/v8/v6/AddTypeToColumnConfigRector.php)

```diff
 return [
     'columns' => [
-        'bar' => []
+        'bar' => [
+            'config' => [
+                'type' => 'none'
+            ]
+        ]
     ]
 ];
```

<br>

## AdditionalFieldProviderRector

Refactor AdditionalFieldProvider classes

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\AdditionalFieldProviderRector`](../src/Rector/v9/v4/AdditionalFieldProviderRector.php)

```diff
-use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;
+use TYPO3\CMS\Scheduler\AbstractAdditionalFieldProvider;
 use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;

-class FileCleanupTaskAdditionalFields implements AdditionalFieldProviderInterface
+class FileCleanupTaskAdditionalFields extends AbstractAdditionalFieldProvider
 {
     public function getAdditionalFields (array &$taskInfo, $task, SchedulerModuleController $parentObject)
     {
         if (!isset($taskInfo[$this->fieldAgeInDays])) {
-            if ($parentObject->CMD == 'edit') {
+            if ((string) $parentObject->getCurrentAction() == 'edit') {
                 $taskInfo[$this->fieldAgeInDays] = (int)$task->ageInDays;
             } else {
                 $taskInfo[$this->fieldAgeInDays] = '';
             }
         }
    }
 }
```

<br>

## AdditionalHeadersToArrayTypoScriptRector

Use array syntax for additionalHeaders

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v7\v1\AdditionalHeadersToArrayTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v7/v1/AdditionalHeadersToArrayTypoScriptRector.php)

```diff
-config.additionalHeaders = Content-type:application/json
+config.additionalHeaders.10.header = Content-type:application/json
```

<br>

## ApacheSolrDocumentToSolariumDocumentRector

Apache_Solr_Document to solarium based document

- class: [`Ssch\TYPO3Rector\Rector\Extensions\solr\v9\ApacheSolrDocumentToSolariumDocumentRector`](../src/Rector/Extensions/solr/v9/ApacheSolrDocumentToSolariumDocumentRector.php)

```diff
 $document = new Apache_Solr_Document();
-$document->setMultiValue('foo', 'bar', true);
+$document->addField('foo', 'bar', true);
```

<br>

## Array2XmlCsToArray2XmlRector

array2xml_cs to array2xml

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\Array2XmlCsToArray2XmlRector`](../src/Rector/v8/v1/Array2XmlCsToArray2XmlRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-GeneralUtility::array2xml_cs();
+GeneralUtility::array2xml();
```

<br>

## ArrayUtilityInArrayToFuncInArrayRector

Method inArray from ArrayUtility to in_array

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\ArrayUtilityInArrayToFuncInArrayRector`](../src/Rector/v8/v6/ArrayUtilityInArrayToFuncInArrayRector.php)

```diff
-ArrayUtility::inArray()
+in_array
```

<br>

## BackendUserAuthenticationSimplelogRector

Migrate the method `BackendUserAuthentication->simplelog()` to `BackendUserAuthentication->writelog()`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\BackendUserAuthenticationSimplelogRector`](../src/Rector/v9/v3/BackendUserAuthenticationSimplelogRector.php)

```diff
 $someObject = GeneralUtility::makeInstance(TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class);
-$someObject->simplelog($message, $extKey, $error);
+$someObject->writelog(4, 0, $error, 0, ($extKey ? '[' . $extKey . '] ' : '') . $message, []);
```

<br>

## BackendUtilityEditOnClickRector

Migrate the method `BackendUtility::editOnClick()` to use UriBuilder API

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\BackendUtilityEditOnClickRector`](../src/Rector/v10/v1/BackendUtilityEditOnClickRector.php)

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br>

## BackendUtilityGetModuleUrlRector

Migrate the method `BackendUtility::getModuleUrl()` to use UriBuilder API

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\BackendUtilityGetModuleUrlRector`](../src/Rector/v9/v3/BackendUtilityGetModuleUrlRector.php)

```diff
 $moduleName = 'record_edit';
 $params = ['pid' => 2];
-$url = BackendUtility::getModuleUrl($moduleName, $params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);
```

<br>

## BackendUtilityGetRecordRawRector

Migrate `BackendUtility::getRecordRaw()` to QueryBuilder

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordRawRector`](../src/Rector/v8/v7/BackendUtilityGetRecordRawRector.php)

```diff
 $table = 'fe_users';
 $where = 'uid > 5';
 $fields = ['uid', 'pid'];
-$record = BackendUtility::getRecordRaw($table, $where, $fields);
+
+$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
+$queryBuilder->getRestrictions()->removeAll();
+
+$record = $queryBuilder->select(GeneralUtility::trimExplode(',', $fields, true))
+    ->from($table)
+    ->where(QueryHelper::stripLogicalOperatorPrefix($where))
+    ->execute()
+    ->fetch();
```

<br>

## BackendUtilityGetRecordsByFieldToQueryBuilderRector

BackendUtility::getRecordsByField to QueryBuilder

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector`](../src/Rector/v8/v7/BackendUtilityGetRecordsByFieldToQueryBuilderRector.php)

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
+use TYPO3\CMS\Core\Database\ConnectionPool;
+use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
+use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
+use TYPO3\CMS\Core\Utility\GeneralUtility;

-$rows = BackendUtility::getRecordsByField('table', 'uid', 3);
+$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
+$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
+$queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
+$queryBuilder->select('*')->from('table')->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(3)));
+$rows = $queryBuilder->execute()->fetchAll();
```

<br>

## BackendUtilityGetViewDomainToPageRouterRector

Refactor method call `BackendUtility::getViewDomain()` to PageRouter

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector`](../src/Rector/v10/v0/BackendUtilityGetViewDomainToPageRouterRector.php)

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
+use TYPO3\CMS\Core\Site\SiteFinder;
+use TYPO3\CMS\Core\Utility\GeneralUtility;

-$domain1 = BackendUtility::getViewDomain(1);
+$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1);
+$domain1 = $site->getRouter()->generateUri(1);
```

<br>

## BackendUtilityShortcutExistsRector

shortcutExists Static call replaced by method call of ShortcutRepository

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\BackendUtilityShortcutExistsRector`](../src/Rector/v9/v4/BackendUtilityShortcutExistsRector.php)

```diff
-TYPO3\CMS\Backend\Utility\BackendUtility::shortcutExists($url);
+GeneralUtility::makeInstance(ShortcutRepository::class)->shortcutExists($url);
```

<br>

## CallEnableFieldsFromPageRepositoryRector

Call enable fields from PageRepository instead of ContentObjectRenderer

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\CallEnableFieldsFromPageRepositoryRector`](../src/Rector/v9/v4/CallEnableFieldsFromPageRepositoryRector.php)

```diff
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->enableFields('pages', false, []);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
```

<br>

## ChangeAttemptsParameterConsoleOutputRector

Turns old default value to parameter in `ConsoleOutput->askAndValidate()` and/or `ConsoleOutput->select()` method

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\ChangeAttemptsParameterConsoleOutputRector`](../src/Rector/v8/v7/ChangeAttemptsParameterConsoleOutputRector.php)

```diff
-$this->output->select('The question', [1, 2, 3], null, false, false);
+$this->output->select('The question', [1, 2, 3], null, false, null);
```

<br>

## ChangeDefaultCachingFrameworkNamesRector

Use new default cache names like core instead of cache_core)

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector`](../src/Rector/v10/v0/ChangeDefaultCachingFrameworkNamesRector.php)

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

## ChangeExtbaseValidatorsRector

Adapt extbase validators to new interface

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ChangeExtbaseValidatorsRector`](../src/Rector/v12/v0/typo3/ChangeExtbaseValidatorsRector.php)

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

## ChangeMethodCallsForStandaloneViewRector

Turns method call names to new ones.

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\ChangeMethodCallsForStandaloneViewRector`](../src/Rector/v8/v0/ChangeMethodCallsForStandaloneViewRector.php)

```diff
 $someObject = new StandaloneView();
-$someObject->setLayoutRootPath();
-$someObject->getLayoutRootPath();
-$someObject->setPartialRootPath();
-$someObject->getPartialRootPath();
+$someObject->setLayoutRootPaths();
+$someObject->getLayoutRootPaths();
+$someObject->setPartialRootPaths();
+$someObject->getPartialRootPaths();
```

<br>

## CharsetConverterToMultiByteFunctionsRector

Move from CharsetConverter methods to mb_string functions

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector`](../src/Rector/v8/v5/CharsetConverterToMultiByteFunctionsRector.php)

```diff
-use TYPO3\CMS\Core\Charset\CharsetConverter;
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
-$charsetConverter->strlen('utf-8', 'string');
+mb_strlen('string', 'utf-8');
```

<br>

## CheckForExtensionInfoRector

Change the extensions to check for info instead of info_pagetsconfig.

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector`](../src/Rector/v9/v0/CheckForExtensionInfoRector.php)

```diff
-if (ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {
+if (ExtensionManagementUtility::isLoaded('info')) {
 }

 $packageManager = GeneralUtility::makeInstance(PackageManager::class);
-if ($packageManager->isActive('info_pagetsconfig')) {
+if ($packageManager->isActive('info')) {
 }
```

<br>

## CheckForExtensionVersionRector

Change the extensions to check for workspaces instead of version.

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector`](../src/Rector/v9/v0/CheckForExtensionVersionRector.php)

```diff
-if (ExtensionManagementUtility::isLoaded('version')) {
+if (ExtensionManagementUtility::isLoaded('workspaces')) {
 }

 $packageManager = GeneralUtility::makeInstance(PackageManager::class);
-if ($packageManager->isActive('version')) {
+if ($packageManager->isActive('workspaces')) {
 }
```

<br>

## ConfigurationManagerAddControllerConfigurationMethodRector

Add additional method getControllerConfiguration for AbstractConfigurationManager

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector`](../src/Rector/v10/v0/ConfigurationManagerAddControllerConfigurationMethodRector.php)

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

## ConstantsToEnvironmentApiCallRector

Turns defined constant to static method call of new Environment API.

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\ConstantsToEnvironmentApiCallRector`](../src/Rector/v9/v4/ConstantsToEnvironmentApiCallRector.php)

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
```

<br>

## ContentObjectRegistrationViaServiceConfigurationRector

ContentObject Registration via service configuration

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ContentObjectRegistrationViaServiceConfigurationRector`](../src/Rector/v12/v0/typo3/ContentObjectRegistrationViaServiceConfigurationRector.php)

```diff
-$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'][Multivalue::CONTENT_OBJECT_NAME] = Multivalue::class;
+// Remove node and add or modify existing Services.yaml in Configuration/Services.yaml
```

<br>

## ContentObjectRendererFileResourceRector

Migrate fileResource method of class ContentObjectRenderer

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\ContentObjectRendererFileResourceRector`](../src/Rector/v8/v5/ContentObjectRendererFileResourceRector.php)

```diff
-$template = $this->cObj->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');
+$path = $GLOBALS['TSFE']->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
+if ($path !== null && file_exists($path)) {
+    $template = file_get_contents($path);
+}
```

<br>

## ConvertImplicitVariablesToExplicitGlobalsRector

Convert `$TYPO3_CONF_VARS` to `$GLOBALS['TYPO3_CONF_VARS']`

- class: [`Ssch\TYPO3Rector\Rector\General\ConvertImplicitVariablesToExplicitGlobalsRector`](../src/Rector/General/ConvertImplicitVariablesToExplicitGlobalsRector.php)

```diff
-$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
+$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_userauth.php']['postUserLookUp']['foo'] = 'FooBarBaz->handle';
```

<br>

## CopyMethodGetPidForModTSconfigRector

Copy method getPidForModTSconfig of class BackendUtility over

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector`](../src/Rector/v9/v3/CopyMethodGetPidForModTSconfigRector.php)

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
+use TYPO3\CMS\Core\Utility\MathUtility;

-BackendUtility::getPidForModTSconfig('pages', 1, 2);
+$table === 'pages' && MathUtility::canBeInterpretedAsInteger($uid) ? $uid : $pid;
```

<br>

## DataHandlerRmCommaRector

Migrate the method `DataHandler::rmComma()` to use `rtrim()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerRmCommaRector`](../src/Rector/v8/v7/DataHandlerRmCommaRector.php)

```diff
 $inList = '1,2,3,';
 $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
-$inList = $dataHandler->rmComma(trim($inList));
+$inList = rtrim(trim($inList), ',');
```

<br>

## DataHandlerVariousMethodsAndMethodArgumentsRector

Remove CharsetConvertParameters

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerVariousMethodsAndMethodArgumentsRector`](../src/Rector/v8/v7/DataHandlerVariousMethodsAndMethodArgumentsRector.php)

```diff
 $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
-$dest = $dataHandler->destPathFromUploadFolder('uploadFolder');
-$dataHandler->extFileFunctions('table', 'field', 'theField', 'deleteAll');
+$dest = PATH_site . 'uploadFolder';
+$dataHandler->extFileFunctions('table', 'field', 'theField');
```

<br>

## DatabaseConnectionToDbalRector

Refactor legacy calls of DatabaseConnection to Dbal

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\DatabaseConnectionToDbalRector`](../src/Rector/v9/v0/DatabaseConnectionToDbalRector.php)

```diff
-$GLOBALS['TYPO3_DB']->exec_INSERTquery(
+use \TYPO3\CMS\Core\Utility\GeneralUtility;
+use \TYPO3\CMS\Core\Database\ConnectionPool;
+
+GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages')->insert(
     'pages',
     [
         'pid' => 0,
         'title' => 'Home',
     ]
 );
```

<br>

## DateTimeAspectInsteadOfGlobalsExecTimeRector

Use DateTimeAspect instead of superglobals like `$GLOBALS['EXEC_TIME']`

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\DateTimeAspectInsteadOfGlobalsExecTimeRector`](../src/Rector/v11/v0/DateTimeAspectInsteadOfGlobalsExecTimeRector.php)

```diff
-$currentTimestamp = $GLOBALS['EXEC_TIME'];
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+
+$currentTimestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
```

<br>

## DefaultSwitchFluidRector

Use <f:defaultCase> instead of <f:case default="1">

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v8\v0\DefaultSwitchFluidRector`](../src/FileProcessor/Fluid/Rector/v8/v0/DefaultSwitchFluidRector.php)

```diff
 <f:switch expression="{someVariable}">
     <f:case value="...">...</f:case>
     <f:case value="...">...</f:case>
     <f:case value="...">...</f:case>
-    <f:case default="1">...</f:case>
+    <f:defaultCase>...</f:defaultCase>
 </f:switch>
```

<br>

## DocumentTemplateAddStyleSheetRector

Use PageRenderer::addCssFile instead of `DocumentTemplate::addStyleSheet()`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\DocumentTemplateAddStyleSheetRector`](../src/Rector/v9/v4/DocumentTemplateAddStyleSheetRector.php)

```diff
-$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
-$documentTemplate->addStyleSheet('foo', 'foo.css');
+GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('foo.css', 'stylesheet', 'screen', '');
```

<br>

## DropAdditionalPaletteRector

TCA: Drop additional palette

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\DropAdditionalPaletteRector`](../src/Rector/v7/v4/DropAdditionalPaletteRector.php)

```diff
 return [
     'types' => [
         'aType' => [
-            'showitem' => 'aField;aLabel;anAdditionalPaletteName',
+            'showitem' => 'aField;aLabel, --palette--;;anAdditionalPaletteName',
         ],
      ],
 ];
```

<br>

## EmailFinisherRector

Convert single recipient values to array for EmailFinisher

- class: [`Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0\EmailFinisherRector`](../src/FileProcessor/Yaml/Form/Rector/v10/v0/EmailFinisherRector.php)

```diff
 finishers:
   -
     options:
-      recipientAddress: bar@domain.com
-      recipientName: 'Bar'
+      recipients:
+        bar@domain.com: 'Bar'
```

<br>

## ExcludeServiceKeysToArrayRector

Change parameter `$excludeServiceKeys` explicity to an array

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\ExcludeServiceKeysToArrayRector`](../src/Rector/v10/v2/ExcludeServiceKeysToArrayRector.php)

```diff
-GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
-ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
+GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
+ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
```

<br>

## ExtEmConfRector

Refactor file ext_emconf.php

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\General\ExtEmConfRector`](../src/Rector/General/ExtEmConfRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;use Ssch\TYPO3Rector\Rector\CodeQuality\Rector\General\ExtEmConfRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => ExtEmConfRector::class,
            'configuration' => [
                'additional_values_to_be_removed' => [
                    'createDirs',
                    'uploadfolder',
                ],
            ],
        ],
    ]);
};
```

↓

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

## ExtbaseCommandControllerToSymfonyCommandRector

Migrate from extbase CommandController to Symfony Command

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector`](../src/Rector/v9/v5/ExtbaseCommandControllerToSymfonyCommandRector.php)

```diff
-use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
+use Symfony\Component\Console\Command\Command;
+use Symfony\Component\Console\Input\InputInterface;
+use Symfony\Component\Console\Output\OutputInterface;

-final class TestCommand extends CommandController
+final class FooCommand extends Command
 {
-    /**
-     * This is the description of the command
-     *
-     * @param string Foo The foo parameter
-     */
-    public function fooCommand(string $foo)
+    protected function configure(): void
     {
+        $this->setDescription('This is the description of the command');
+        $this->addArgument('foo', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The foo parameter', null);
+    }

+    protected function execute(InputInterface $input, OutputInterface $output): int
+    {
+        return 0;
     }
 }
```

<br>

## ExtbaseControllerActionsMustReturnResponseInterfaceRector

Extbase controller actions must return ResponseInterface

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector`](../src/Rector/v11/v0/ExtbaseControllerActionsMustReturnResponseInterfaceRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;use Ssch\TYPO3Rector\Rector\TYPO311\v0\ExtbaseControllerActionsMustReturnResponseInterfaceRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => ExtbaseControllerActionsMustReturnResponseInterfaceRector::class,
            'configuration' => [
                'redirect_methods' => [
                    'myRedirectMethod',
                ],
            ],
        ],
    ]);
};
```

↓

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

## ExtbasePersistenceTypoScriptRector

Convert extbase TypoScript persistence configuration to classes one

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v10\v0\ExtbasePersistenceTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v10/v0/ExtbasePersistenceTypoScriptRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v10\v0\ExtbasePersistenceTypoScriptRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => ExtbasePersistenceTypoScriptRector::class,
            'configuration' => [
                'filename' => 'path/to/Configuration/Extbase/Persistence/Classes.php',
            ],
        ],
    ]);
};
```

↓

```diff
-config.tx_extbase.persistence.classes {
-    GeorgRinger\News\Domain\Model\FileReference {
-        mapping {
-            tableName = sys_file_reference
-        }
-    }
-}
+return [
+    \GeorgRinger\News\Domain\Model\FileReference::class => [
+        'tableName' => 'sys_file_reference',
+    ],
+];
```

<br>

## ExtensionManagementUtilityExtRelPathRector

Substitute `ExtensionManagementUtility::extRelPath()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v4\ExtensionManagementUtilityExtRelPathRector`](../src/Rector/v8/v4/ExtensionManagementUtilityExtRelPathRector.php)

```diff
+use TYPO3\CMS\Core\Utility\PathUtility;
 use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

-$relPath = ExtensionManagementUtility::extRelPath('my_extension');
+$relPath = PathUtility::getAbsoluteWebPath(ExtensionManagementUtility::extPath('my_extension'));
```

<br>

## FileIncludeToImportStatementTypoScriptRector

Convert old include statement to new import syntax

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v9\v0\FileIncludeToImportStatementTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v9/v0/FileIncludeToImportStatementTypoScriptRector.php)

```diff
-<INCLUDE_TYPOSCRIPT: source="FILE:conditions.typoscript">
+@import conditions.typoscript
```

<br>

## FindByPidsAndAuthorIdRector

Use findByPidsAndAuthorId instead of findByPidsAndAuthor

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector`](../src/Rector/v9/v0/FindByPidsAndAuthorIdRector.php)

```diff
 $sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
 $backendUser = new BackendUser();
-$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
+$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
```

<br>

## FlexFormToolsArrayValueByPathRector

Replace deprecated FlexFormTools methods with ArrayUtility methods

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\FlexFormToolsArrayValueByPathRector`](../src/Rector/v11/v5/FlexFormToolsArrayValueByPathRector.php)

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

## ForceTemplateParsingInTsfeAndTemplateServiceRector

Force template parsing in tsfe is replaced with context api and aspects

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector`](../src/Rector/v10/v0/ForceTemplateParsingInTsfeAndTemplateServiceRector.php)

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

## ForwardResponseInsteadOfForwardMethodRector

Return `TYPO3\CMS\Extbase\Http\ForwardResponse` instead of `TYPO3\CMS\Extbase\Mvc\Controller\ActionController::forward()`

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\ForwardResponseInsteadOfForwardMethodRector`](../src/Rector/v11/v0/ForwardResponseInsteadOfForwardMethodRector.php)

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

## GeneralUtilityGetHostNameToGetIndpEnvRector

Migrating method call `GeneralUtility::getHostname()` to GeneralUtility::getIndpEnv('HTTP_HOST')

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\GeneralUtilityGetHostNameToGetIndpEnvRector`](../src/Rector/v9/v4/GeneralUtilityGetHostNameToGetIndpEnvRector.php)

```diff
-\TYPO3\CMS\Core\Utility\GeneralUtility::getHostname();
+\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('HTTP_HOST')
```

<br>

## GeneralUtilityGetUrlRequestHeadersRector

Refactor `GeneralUtility::getUrl()` request headers in a associative way

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\GeneralUtilityGetUrlRequestHeadersRector`](../src/Rector/v9/v2/GeneralUtilityGetUrlRequestHeadersRector.php)

```diff
-GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language: de-DE']);
+GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language' => 'de-DE']);
```

<br>

## GeneralUtilityToUpperAndLowerRector

Use mb_strtolower and mb_strtoupper

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\GeneralUtilityToUpperAndLowerRector`](../src/Rector/v8/v1/GeneralUtilityToUpperAndLowerRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-$toUpper = GeneralUtility::strtoupper('foo');
-$toLower = GeneralUtility::strtolower('FOO');
+$toUpper = mb_strtoupper('foo', 'utf-8');
+$toLower = mb_strtolower('FOO', 'utf-8');
```

<br>

## GeneratePageTitleRector

Use generatePageTitle of TSFE instead of class PageGenerator

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector`](../src/Rector/v9/v0/GeneratePageTitleRector.php)

```diff
 use TYPO3\CMS\Frontend\Page\PageGenerator;

-PageGenerator::generatePageTitle();
+$GLOBALS['TSFE']->generatePageTitle();
```

<br>

## GetClickMenuOnIconTagParametersRector

Use `BackendUtility::getClickMenuOnIconTagParameters()` instead `BackendUtility::wrapClickMenuOnIcon()` if needed

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\GetClickMenuOnIconTagParametersRector`](../src/Rector/v11/v0/GetClickMenuOnIconTagParametersRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
 $returnTagParameters = true;
-BackendUtility::wrapClickMenuOnIcon('pages', 1, 'foo', '', '', '', $returnTagParameters);
+BackendUtility::getClickMenuOnIconTagParameters('pages', 1, 'foo');
```

<br>

## GetFileAbsFileNameRemoveDeprecatedArgumentsRector

Remove second and third argument of `GeneralUtility::getFileAbsFileName()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\GetFileAbsFileNameRemoveDeprecatedArgumentsRector`](../src/Rector/v8/v0/GetFileAbsFileNameRemoveDeprecatedArgumentsRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::getFileAbsFileName('foo.txt', false, true);
+GeneralUtility::getFileAbsFileName('foo.txt');
```

<br>

## GetPreferredClientLanguageRector

Use `Locales->getPreferredClientLanguage()` instead of `CharsetConverter::getPreferredClientLanguage()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\GetPreferredClientLanguageRector`](../src/Rector/v8/v0/GetPreferredClientLanguageRector.php)

```diff
+use TYPO3\CMS\Core\Localization\Locales;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$preferredLanguage = $GLOBALS['TSFE']->csConvObj->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
+$preferredLanguage = GeneralUtility::makeInstance(Locales::class)->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
```

<br>

## GetTemporaryImageWithTextRector

Use GraphicalFunctions->getTemporaryImageWithText instead of LocalImageProcessor->getTemporaryImageWithText

- class: [`Ssch\TYPO3Rector\Rector\v7\v1\GetTemporaryImageWithTextRector`](../src/Rector/v7/v1/GetTemporaryImageWithTextRector.php)

```diff
-GeneralUtility::makeInstance(LocalImageProcessor::class)->getTemporaryImageWithText("foo", "bar", "baz", "foo")
+GeneralUtility::makeInstance(GraphicalFunctions::class)->getTemporaryImageWithText("foo", "bar", "baz", "foo")
```

<br>

## HandleCObjRendererATagParamsMethodRector

Removes deprecated params of the `ContentObjectRenderer->getATagParams()` method

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\HandleCObjRendererATagParamsMethodRector`](../src/Rector/v11/v5/HandleCObjRendererATagParamsMethodRector.php)

```diff
 $cObjRenderer = GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
-$bar = $cObjRenderer->getATagParams([], false);
+$bar = $cObjRenderer->getATagParams([]);
```

<br>

## HintNecessaryUploadedFileChangesRector

Add FIXME comment for necessary changes for addUploadedFile overrides

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\HintNecessaryUploadedFileChangesRector`](../src/Rector/v12/v0/typo3/HintNecessaryUploadedFileChangesRector.php)

```diff
+// FIXME: Rector: https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-97214-UseUploadedFileObjectsInsteadOf_FILES.html
 public function addUploadedFile(array $uploadedFileData)
 {
 }
```

<br>

## IconsRector

Copy ext_icon.* to Resources/Icons/Extension.*

- class: [`Ssch\TYPO3Rector\FileProcessor\Resources\Icons\Rector\v8\v3\IconsRector`](../src/FileProcessor/Resources/Icons/Rector/v8/v3/IconsRector.php)

```diff
-ext_icon.gif
+Resources/Icons/Extension.gif
```

<br>

## IgnoreValidationAnnotationRector

Turns properties with `@ignorevalidation` to properties with `@TYPO3\CMS\Extbase\Annotation\IgnoreValidation`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector`](../src/Rector/v9/v0/IgnoreValidationAnnotationRector.php)

```diff
+use TYPO3\CMS\Extbase\Annotation as Extbase;
 /**
- * @ignorevalidation $param
+ * @Extbase\IgnoreValidation("param")
  */
 public function method($param)
 {
 }
```

<br>

## ImplementSiteLanguageAwareInterfaceRector

Implement SiteLanguageAwareInterface instead of using SiteLanguageAwareTrait

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ImplementSiteLanguageAwareInterfaceRector`](../src/Rector/v12/v0/typo3/ImplementSiteLanguageAwareInterfaceRector.php)

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

## InjectAnnotationRector

Turns properties with `@inject` to setter injection

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector`](../src/Rector/v9/v0/InjectAnnotationRector.php)

```diff
 /**
  * @var SomeService
- * @inject
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

## InjectEnvironmentServiceIfNeededInResponseRector

Inject EnvironmentService if needed in subclass of Response

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\InjectEnvironmentServiceIfNeededInResponseRector`](../src/Rector/v10/v2/InjectEnvironmentServiceIfNeededInResponseRector.php)

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

## InjectMethodToConstructorInjectionRector



- class: [`Ssch\TYPO3Rector\Rector\General\InjectMethodToConstructorInjectionRector`](../src/Rector/General/InjectMethodToConstructorInjectionRector.php)

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

## InstantiatePageRendererExplicitlyRector

Instantiate PageRenderer explicitly

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\InstantiatePageRendererExplicitlyRector`](../src/Rector/v7/v4/InstantiatePageRendererExplicitlyRector.php)

```diff
-$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
+$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
```

<br>

## LibFluidContentToContentElementTypoScriptPostRector

Convert lib.fluidContent to lib.contentElement

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\PostRector\v8\v7\LibFluidContentToContentElementTypoScriptPostRector`](../src/FileProcessor/TypoScript/PostRector/v8/v7/LibFluidContentToContentElementTypoScriptPostRector.php)

```diff
-lib.fluidContent.templateRootPaths.200 = EXT:your_extension_key/Resources/Private/Templates/
+lib.contentElement.templateRootPaths.200 = EXT:your_extension_key/Resources/Private/Templates/
```

<br>

## LibFluidContentToLibContentElementRector

Convert lib.fluidContent to lib.contentElement

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v8\v7\LibFluidContentToLibContentElementRector`](../src/FileProcessor/TypoScript/Rector/v8/v7/LibFluidContentToLibContentElementRector.php)

```diff
-lib.fluidContent {
+lib.contentElement {
    templateRootPaths {
       200 = EXT:your_extension_key/Resources/Private/Templates/
    }
    partialRootPaths {
       200 = EXT:your_extension_key/Resources/Private/Partials/
    }
    layoutRootPaths {
       200 = EXT:your_extension_key/Resources/Private/Layouts/
    }
 }
```

<br>

## MetaTagManagementRector

Use setMetaTag method from PageRenderer class

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector`](../src/Rector/v9/v0/MetaTagManagementRector.php)

```diff
 use TYPO3\CMS\Core\Page\PageRenderer;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$pageRenderer->addMetaTag('<meta name="keywords" content="seo, search engine optimisation, search engine optimization, search engine ranking">');
+$pageRenderer->setMetaTag('name', 'keywords', 'seo, search engine optimisation, search engine optimization, search engine ranking');
```

<br>

## MethodGetInstanceToMakeInstanceCallRector

Use GeneralUtility::makeInstance instead of getInstance call

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\General\MethodGetInstanceToMakeInstanceCallRector`](../src/Rector/General/MethodGetInstanceToMakeInstanceCallRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;use Ssch\TYPO3Rector\Rector\CodeQuality\Rector\General\MethodGetInstanceToMakeInstanceCallRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => MethodGetInstanceToMakeInstanceCallRector::class,
            'configuration' => [
                'classes-get-instance-to-make-instance' => [
                    'SomeClass',
                ],
            ],
        ],
    ]);
};
```

↓

```diff
-$instance = TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
+use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
+
+$instance = GeneralUtility::makeInstance(ExtractorRegistry::class);
```

<br>

## MethodReadLLFileToLocalizationFactoryRector

Use LocalizationFactory->getParsedData instead of GeneralUtility::readLLfile

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\MethodReadLLFileToLocalizationFactoryRector`](../src/Rector/v7/v4/MethodReadLLFileToLocalizationFactoryRector.php)

```diff
+use TYPO3\CMS\Core\Localization\LocalizationFactory;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$locallangs = GeneralUtility::readLLfile('EXT:foo/locallang.xml', 'de');
+$locallangs = GeneralUtility::makeInstance(LocalizationFactory::class)->getParsedData('EXT:foo/locallang.xml', 'de');
```

<br>

## MigrateColsToSizeForTcaTypeNoneRector

Migrates option cols to size for TCA type none

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateColsToSizeForTcaTypeNoneRector`](../src/Rector/v12/v0/tca/MigrateColsToSizeForTcaTypeNoneRector.php)

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

## MigrateEmailFlagToEmailTypeFlexFormRector

Migrate email flag to email type

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEmailFlagToEmailTypeFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateEmailFlagToEmailTypeFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <email_field>
                 <label>Email</label>
                 <config>
-                    <type>input</type>
-                    <eval>trim,email</eval>
-                    <max>255</max>
+                    <type>email</type>
                 </config>
             </email_field>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector

Migrate eval int and double2 to type number

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateEvalIntAndDouble2ToTypeNumberFlexFormRector.php)

```diff
 <int_field>
     <label>int field</label>
     <config>
-        <type>input</type>
-        <eval>int</eval>
+        <type>number</type>
     </config>
 </int_field>
 <double2_field>
     <label>double2 field</label>
     <config>
-        <type>input</type>
-        <eval>double2</eval>
+        <type>number</type>
+        <format>decimal</format>
     </config>
 </double2_field>
```

<br>

## MigrateEvalIntAndDouble2ToTypeNumberRector

Migrate eval int and double2 to type number

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateEvalIntAndDouble2ToTypeNumberRector`](../src/Rector/v12/v0/tca/MigrateEvalIntAndDouble2ToTypeNumberRector.php)

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

## MigrateFileFolderConfigurationRector

Migrate file folder config

- class: [`Ssch\TYPO3Rector\Rector\v11\v4\MigrateFileFolderConfigurationRector`](../src/Rector/v11/v4/MigrateFileFolderConfigurationRector.php)

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

## MigrateFrameModuleToSvgTreeRector

Migrate the iframe based file tree to SVG

- class: [`Ssch\TYPO3Rector\Rector\v11\v2\MigrateFrameModuleToSvgTreeRector`](../src/Rector/v11/v2/MigrateFrameModuleToSvgTreeRector.php)

```diff
-'navigationFrameModule' => 'file_navframe'
+'navigationComponentId' => 'TYPO3/CMS/Backend/Tree/FileStorageTreeContainer'
```

<br>

## MigrateInputDateTimeRector

Migrate renderType inputDateTime to new TCA type datetime

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateInputDateTimeRector`](../src/Rector/v12/v0/tca/MigrateInputDateTimeRector.php)

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

## MigrateInternalTypeFolderToTypeFolderFlexFormRector

Migrates TCA internal_type into new new TCA type folder

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateInternalTypeFolderToTypeFolderFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateInternalTypeFolderToTypeFolderFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <aFlexField>
                 <label>aFlexFieldLabel</label>
                 <config>
-                    <type>group</type>
-                    <internal_type>folder</internal_type>
+                    <type>folder</type>
                 </config>
             </aFlexField>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigrateInternalTypeFolderToTypeFolderRector

Migrates TCA internal_type into new new TCA type folder

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateInternalTypeFolderToTypeFolderRector`](../src/Rector/v12/v0/tca/MigrateInternalTypeFolderToTypeFolderRector.php)

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

## MigrateItemsIndexedKeysToAssociativeRector

Migrates indexed item array keys to associative for type select, radio and check

- class: [`Ssch\TYPO3Rector\Rector\v12\v3\tca\MigrateItemsIndexedKeysToAssociativeRector`](../src/Rector/v12/v3/tca/MigrateItemsIndexedKeysToAssociativeRector.php)

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

## MigrateItemsToIndexedArrayKeysForFlexFormItemsRector

Migrates indexed item array keys to associative for type select, radio and check in FlexForms. This Rector Rule is sponsored by UDG Rhein-Main GmbH

- class: [`Ssch\TYPO3Rector\Rector\v12\v3\flexform\MigrateItemsToIndexedArrayKeysForFlexFormItemsRector`](../src/Rector/v12/v3/flexform/MigrateItemsToIndexedArrayKeysForFlexFormItemsRector.php)

```diff
 <select_single_1>
     <label>select_single_1 description</label>
     <description>field description</description>
     <config>
         <type>select</type>
         <renderType>selectSingle</renderType>
         <items>
             <numIndex index="0">
-                <numIndex index="0">Label 1</numIndex>
-                <numIndex index="1">value1</numIndex>
+                <label>Label 1</label>
+                <value>value1</value>
             </numIndex>
             <numIndex index="1">
-                <numIndex index="0">Label 2</numIndex>
-                <numIndex index="1">value2</numIndex>
+                <label>Label 2</label>
+                <value>value2</value>
             </numIndex>
         </items>
     </config>
 </select_single_1>
```

<br>

## MigrateLanguageFieldToTcaTypeLanguageRector

use the new TCA type language instead of foreign_table => sys_language for selecting a records

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\MigrateLanguageFieldToTcaTypeLanguageRector`](../src/Rector/v11/v3/MigrateLanguageFieldToTcaTypeLanguageRector.php)

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

## MigrateLastPiecesOfDefaultExtrasRector

Migrate last pieces of default extras

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MigrateLastPiecesOfDefaultExtrasRector`](../src/Rector/v8/v6/MigrateLastPiecesOfDefaultExtrasRector.php)

```diff
 return [
     'columns' => [
         'constants' => [
             'label' => 'Foo',
             'config' => [
                 'type' => 'text',
                 'cols' => 48,
                 'rows' => 15,
-            ],
-            'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
+                'wrap' => 'off',
+                'enableTabulator' => true,
+                'fixedFont' => true,
+            ]
         ],
     ],
     'types' => [
         'myType' => [
             'columnsOverrides' => [
                 'constants' => [
                     'label' => 'Foo',
                     'config' => [
                         'type' => 'text',
                         'cols' => 48,
                         'rows' => 15,
-                    ],
-                    'defaultExtras' => 'rte_only:nowrap:enable-tab:fixed-font'
+                        'wrap' => 'off',
+                        'enableTabulator' => true,
+                        'fixedFont' => true,
+                    ]
                 ],
             ],
         ],
     ],
 ];
```

<br>

## MigrateMagicRepositoryMethodsRector

Migrate the magic findBy methods

- class: [`Ssch\TYPO3Rector\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector`](../src/Rector/v12/v3/typo3/MigrateMagicRepositoryMethodsRector.php)

```diff
-$blogRepository->findByFooBar('bar');
-$blogRepository->findOneByFoo('bar');
-$blogRepository->countByFoo('bar');
+$blogRepository->findBy(['fooBar' => 'bar']);
+$blogRepository->findOneBy(['foo' => 'bar']);
+$blogRepository->count(['foo' => 'bar']);
```

<br>

## MigrateNullFlagFlexFormRector

Migrate null flag

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateNullFlagFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateNullFlagFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <aFlexField>
                 <label>aFlexFieldLabel</label>
                 <config>
-                    <eval>null</eval>
+                    <nullable>true</nullable>
                 </config>
             </aFlexField>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigrateNullFlagRector

Migrate null flag

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateNullFlagRector`](../src/Rector/v12/v0/tca/MigrateNullFlagRector.php)

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

## MigrateOptionsOfTypeGroupRector

Migrate options if type group in TCA

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MigrateOptionsOfTypeGroupRector`](../src/Rector/v8/v6/MigrateOptionsOfTypeGroupRector.php)

```diff
 return [
     'ctrl' => [],
     'columns' => [
         'image2' => [
             'config' => [
-                'selectedListStyle' => 'foo',
                 'type' => 'group',
                 'internal_type' => 'file',
-                'show_thumbs' => '0',
-                'disable_controls' => 'browser'
+                'fieldControl' => [
+                    'elementBrowser' => ['disabled' => true]
+                ],
+                'fieldWizard' => [
+                    'fileThumbnails' => ['disabled' => true]
+                ]
             ],
         ],
     ],
 ];
```

<br>

## MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector

Migrate password and salted password to password type

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector`](../src/Rector/v12/v0/flexform/MigratePasswordAndSaltedPasswordToPasswordTypeFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <password_field>
                 <label>Password</label>
                 <config>
-                    <type>input</type>
-                    <eval>trim,password,saltedPassword</eval>
+                    <type>password</type>
                 </config>
             </password_field>
             <another_password_field>
                 <label>Password</label>
                 <config>
-                    <type>input</type>
-                    <eval>trim,password</eval>
+                    <type>password</type>
+                    <hashed>false</hashed>
                 </config>
             </another_password_field>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigratePasswordAndSaltedPasswordToPasswordTypeRector

Migrate password and salted password to password type

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigratePasswordAndSaltedPasswordToPasswordTypeRector`](../src/Rector/v12/v0/tca/MigratePasswordAndSaltedPasswordToPasswordTypeRector.php)

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

## MigrateQueryBuilderExecuteRector

Replace `Querybuilder::execute()` with fitting methods

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\MigrateQueryBuilderExecuteRector`](../src/Rector/v12/v0/typo3/MigrateQueryBuilderExecuteRector.php)

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

## MigrateRenderTypeColorpickerToTypeColorFlexFormRector

Migrate renderType colorpicker to type color

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateRenderTypeColorpickerToTypeColorFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateRenderTypeColorpickerToTypeColorFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <a_color_field>
                 <label>Color field</label>
                 <config>
-                    <type>input</type>
-                    <renderType>colorpicker</renderType>
+                    <type>color</type>
                     <required>1</required>
                     <size>20</size>
-                    <max>1234</max>
-                    <eval>trim,null</eval>
                     <valuePicker>
                         <items type="array">
                             <numIndex index="0" type="array">
                                 <numIndex index="0">typo3 orange</numIndex>
                                 <numIndex index="1">#FF8700</numIndex>
                             </numIndex>
                         </items>
                     </valuePicker>
                 </config>
             </a_color_field>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigrateRenderTypeColorpickerToTypeColorRector

Migrate renderType colorpicker to type color

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateRenderTypeColorpickerToTypeColorRector`](../src/Rector/v12/v0/tca/MigrateRenderTypeColorpickerToTypeColorRector.php)

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

## MigrateRequiredFlagFlexFormRector

Migrate required flag

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\MigrateRequiredFlagFlexFormRector`](../src/Rector/v12/v0/flexform/MigrateRequiredFlagFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <some_column>
                 <title>foo</title>
                 <config>
-                    <eval>trim,required</eval>
+                    <eval>trim</eval>
+                    <required>1</required>
                 </config>
             </some_column>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## MigrateRequiredFlagRector

Migrate required flag

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateRequiredFlagRector`](../src/Rector/v12/v0/tca/MigrateRequiredFlagRector.php)

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

## MigrateSelectShowIconTableRector

Migrate select showIconTable

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MigrateSelectShowIconTableRector`](../src/Rector/v8/v6/MigrateSelectShowIconTableRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'foo' => [
             'config' => [
                 'type' => 'select',
                 'items' => [
                     ['foo 1', 'foo1', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                     ['foo 2', 'foo2', 'EXT:styleguide/Resources/Public/Icons/tx_styleguide.svg'],
                 ],
                 'renderType' => 'selectSingle',
-                'selicon_cols' => 16,
-                'showIconTable' => true
+                'fieldWizard' => [
+                    'selectIcons' => [
+                        'disabled' => false,
+                    ],
+                ],
             ],
         ],
     ],
 ];
```

<br>

## MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector

Move special configuration to columns overrides

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector`](../src/Rector/v8/v6/MigrateSpecialConfigurationAndRemoveShowItemStylePointerConfigRector.php)

```diff
 return [
     'types' => [
         0 => [
-            'showitem' => 'aField,anotherField;with;;nowrap,thirdField',
+            'showitem' => 'aField,anotherField;with,thirdField',
+            'columnsOverrides' => [
+                'anotherField' => [
+                    'defaultExtras' => 'nowrap',
+                ],
+            ],
         ],
     ],
 ];
```

<br>

## MigrateSpecialLanguagesToTcaTypeLanguageRector

use the new TCA type language instead of foreign_table => sys_language for selecting a records

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\MigrateSpecialLanguagesToTcaTypeLanguageRector`](../src/Rector/v11/v3/MigrateSpecialLanguagesToTcaTypeLanguageRector.php)

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

## MigrateT3editorWizardToRenderTypeT3editorRector

t3editor is no longer configured and enabled as wizard

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\MigrateT3editorWizardToRenderTypeT3editorRector`](../src/Rector/v7/v6/MigrateT3editorWizardToRenderTypeT3editorRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'bodytext' => [
             'config' => [
                 'type' => 'text',
                 'rows' => '42',
-                'wizards' => [
-                    't3editor' => [
-                        'type' => 'userFunc',
-                        'userFunc' => 'TYPO3\CMS\T3editor\FormWizard->main',
-                        'title' => 't3editor',
-                        'icon' => 'wizard_table.gif',
-                        'module' => [
-                            'name' => 'wizard_table'
-                        ],
-                        'params' => [
-                            'format' => 'html',
-                            'style' => 'width:98%; height: 60%;'
-                        ],
-                    ],
-                ],
+                'renderType' => 't3editor',
+                'format' => 'html',
             ],
         ],
     ],
 ];
```

<br>

## MigrateToEmailTypeRector

Migrates existing input TCA with eval email to new TCA type email

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\MigrateToEmailTypeRector`](../src/Rector/v12/v0/tca/MigrateToEmailTypeRector.php)

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

## MigrateXhtmlDoctypeRector

Migrate typoscript xhtmlDoctype to doctype

- class: [`Ssch\TYPO3Rector\Rector\v12\v4\typoscript\MigrateXhtmlDoctypeRector`](../src/Rector/v12/v4/typoscript/MigrateXhtmlDoctypeRector.php)

```diff
-config.xhtmlDoctype = 1
+config.doctype = 1
```

<br>

## MoveApplicationContextToEnvironmentApiRector

Use Environment API to fetch application context

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\MoveApplicationContextToEnvironmentApiRector`](../src/Rector/v10/v2/MoveApplicationContextToEnvironmentApiRector.php)

```diff
-GeneralUtility::getApplicationContext();
+Environment::getContext();
```

<br>

## MoveForeignTypesToOverrideChildTcaRector

TCA InlineOverrideChildTca

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\MoveForeignTypesToOverrideChildTcaRector`](../src/Rector/v8/v7/MoveForeignTypesToOverrideChildTcaRector.php)

```diff
 return [
     'columns' => [
         'aField' => [
             'config' => [
                 'type' => 'inline',
-                'foreign_types' => [
-                    'aForeignType' => [
-                        'showitem' => 'aChildField',
+                'overrideChildTca' => [
+                    'types' => [
+                        'aForeignType' => [
+                            'showitem' => 'aChildField',
+                        ],
                     ],
                 ],
             ],
         ],
     ],
 ];
```

<br>

## MoveLanguageFilesFromExtensionLangRector

Move language resources from ext:lang to their new locations

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector`](../src/Rector/v9/v3/MoveLanguageFilesFromExtensionLangRector.php)

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.no_title');
+$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.no_title');
```

<br>

## MoveLanguageFilesFromLocallangToResourcesRector

Move language files from EXT:lang/locallang_* to Resources/Private/Language

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\MoveLanguageFilesFromLocallangToResourcesRector`](../src/Rector/v8/v5/MoveLanguageFilesFromLocallangToResourcesRector.php)

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:lang/locallang_alt_doc.xlf:label.confirm.delete_record.title');
+$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_alt_doc.xlf:label.confirm.delete_record.title');
```

<br>

## MoveLanguageFilesFromRemovedCmsExtensionRector

Move language files of removed cms to new location

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector`](../src/Rector/v7/v4/MoveLanguageFilesFromRemovedCmsExtensionRector.php)

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:cms/web_info/locallang.xlf:pages_1');
+$languageService->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf:pages_1');
```

<br>

## MoveRenderArgumentsToInitializeArgumentsMethodRector

Move render method arguments to initializeArguments method

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector`](../src/Rector/v9/v0/MoveRenderArgumentsToInitializeArgumentsMethodRector.php)

```diff
 class MyViewHelper implements ViewHelperInterface
 {
-    public function render(array $firstParameter, string $secondParameter = null)
+    public function initializeArguments()
     {
+        $this->registerArgument('firstParameter', 'array', '', true);
+        $this->registerArgument('secondParameter', 'string', '', false, null);
+    }
+
+    public function render()
+    {
+        $firstParameter = $this->arguments['firstParameter'];
+        $secondParameter = $this->arguments['secondParameter'];
     }
 }
```

<br>

## MoveRequestUpdateOptionFromControlToColumnsRector

TCA ctrl field requestUpdate dropped

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MoveRequestUpdateOptionFromControlToColumnsRector`](../src/Rector/v8/v6/MoveRequestUpdateOptionFromControlToColumnsRector.php)

```diff
 return [
     'ctrl' => [
-        'requestUpdate' => 'foo',
     ],
     'columns' => [
-        'foo' => []
+        'foo' => [
+            'onChange' => 'reload'
+        ]
     ]
 ];
```

<br>

## MoveTypeGroupSuggestWizardToSuggestOptionsRector

Migrate the "suggest" wizard in type=group to "hideSuggest" and "suggestOptions"

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\MoveTypeGroupSuggestWizardToSuggestOptionsRector`](../src/Rector/v8/v6/MoveTypeGroupSuggestWizardToSuggestOptionsRector.php)

```diff
 [
     'columns' => [
         'group_db_8' => [
             'label' => 'group_db_8',
             'config' => [
                 'type' => 'group',
                 'internal_type' => 'db',
                 'allowed' => 'tx_styleguide_staticdata',
-                'wizards' => [
-                    '_POSITION' => 'top',
-                    'suggest' => [
-                        'type' => 'suggest',
-                        'default' => [
-                            'pidList' => 42,
-                        ],
-                    ],
+                'suggestOptions' => [
+                    'default' => [
+                        'pidList' => 42,
+                    ]
                 ],
             ],
         ],
     ],
 ];
```

<br>

## ObjectManagerGetToConstructorInjectionRector

Turns fetching of dependencies via `$objectManager->get()` to constructor injection

- class: [`Ssch\TYPO3Rector\Rector\Experimental\ObjectManagerGetToConstructorInjectionRector`](../src/Rector/Experimental/ObjectManagerGetToConstructorInjectionRector.php)

```diff
 final class MyController extends ActionController
 {
+    private SomeService $someService;
+
+    public function __construct(SomeService $someService)
+    {
+        $this->someService = $someService;
+    }
+
     public function someAction()
     {
-        $someService = $this->objectManager->get(SomeService::class);
+        $someService = $this->someService;
     }
 }
```

<br>

## OldConditionToExpressionLanguageTypoScriptRector

Convert old conditions to Symfony Expression Language

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v9\v4\OldConditionToExpressionLanguageTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v9/v4/OldConditionToExpressionLanguageTypoScriptRector.php)

```diff
-[globalVar = TSFE:id=17, TSFE:id=24]
+[getTSFE().id in [17,24]]
```

<br>

## OptionalConstructorToHardRequirementRector

Option constructor arguments to hard requirement

- class: [`Ssch\TYPO3Rector\Rector\Experimental\OptionalConstructorToHardRequirementRector`](../src/Rector/Experimental/OptionalConstructorToHardRequirementRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-use TYPO3\CMS\Extbase\Object\ObjectManager;
 use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
 use TYPO3\CMS\Fluid\View\StandaloneView;

 class MyClass
 {
-    public function __construct(Dispatcher $dispatcher = null, StandaloneView $view = null, BackendUtility $backendUtility = null, string $test = null)
+    public function __construct(Dispatcher $dispatcher, StandaloneView $view, BackendUtility $backendUtility, string $test = null)
     {
-        $dispatcher = $dispatcher ?? GeneralUtility::makeInstance(ObjectManager::class)->get(Dispatcher::class);
-        $view = $view ?? GeneralUtility::makeInstance(StandaloneView::class);
-        $backendUtility = $backendUtility ?? GeneralUtility::makeInstance(BackendUtility::class);
+        $dispatcher = $dispatcher;
+        $view = $view;
+        $backendUtility = $backendUtility;
     }
 }
```

<br>

## PageNotFoundAndErrorHandlingRector

Page Not Found And Error handling in Frontend

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\PageNotFoundAndErrorHandlingRector`](../src/Rector/v9/v2/PageNotFoundAndErrorHandlingRector.php)

```diff
+use TYPO3\CMS\Core\Http\ImmediateResponseException;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
+use TYPO3\CMS\Frontend\Controller\ErrorController;

 class SomeController extends ActionController
 {
     public function unavailableAction(): void
     {
         $message = 'No entry found.';
-        $GLOBALS['TSFE']->pageUnavailableAndExit($message);
+        $response = GeneralUtility::makeInstance(ErrorController::class)->unavailableAction($GLOBALS['TYPO3_REQUEST'], $message);
+        throw new ImmediateResponseException($response);
     }
 }
```

<br>

## PhpOptionsUtilityRector

Refactor methods from PhpOptionsUtility

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\PhpOptionsUtilityRector`](../src/Rector/v9/v3/PhpOptionsUtilityRector.php)

```diff
-PhpOptionsUtility::isSessionAutoStartEnabled()
+filter_var(ini_get('session.auto_start'), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE])
```

<br>

## PrependAbsolutePathToGetFileAbsFileNameRector

Use `GeneralUtility::getFileAbsFileName()` instead of `GraphicalFunctions->prependAbsolutePath()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\PrependAbsolutePathToGetFileAbsFileNameRector`](../src/Rector/v8/v0/PrependAbsolutePathToGetFileAbsFileNameRector.php)

```diff
+use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

 class SomeFooBar
 {
     private $graphicalFunctions;

     public function __construct(GraphicalFunctions $graphicalFunctions)
     {
         $this->graphicalFunctions = $graphicalFunctions;
-        $this->graphicalFunctions->prependAbsolutePath('some.font');
+        GeneralUtility::getFileAbsFileName('some.font');
     }
 }
```

<br>

## PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector

Use method getTSConfig instead of property userTS

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector`](../src/Rector/v9/v3/PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector.php)

```diff
-if(is_array($GLOBALS['BE_USER']->userTS['tx_news.']) && $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1') {
+if(is_array($GLOBALS['BE_USER']->getTSConfig()['tx_news.']) && $GLOBALS['BE_USER']->getTSConfig()['tx_news.']['singleCategoryAcl'] === '1') {
     return true;
 }
```

<br>

## ProvideCObjViaMethodRector

Replaces public `$cObj` with protected and set via method

- class: [`Ssch\TYPO3Rector\Rector\v11\v4\ProvideCObjViaMethodRector`](../src/Rector/v11/v4/ProvideCObjViaMethodRector.php)

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

## QueryLogicalOrAndLogicalAndToArrayParameterRector

Use array instead of multiple parameters for logicalOr and logicalAnd of Extbase Query class

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\QueryLogicalOrAndLogicalAndToArrayParameterRector`](../src/Rector/v9/v0/QueryLogicalOrAndLogicalAndToArrayParameterRector.php)

```diff
 use TYPO3\CMS\Extbase\Persistence\Repository;

 class ProductRepositoryLogicalAnd extends Repository
 {
     public function findAllForList()
     {
         $query = $this->createQuery();
-        $query->matching($query->logicalAnd(
+        $query->matching($query->logicalAnd([
             $query->lessThan('foo', 1),
             $query->lessThan('bar', 1)
-        ));
+        ]));
     }
 }
```

<br>

## RandomMethodsToRandomClassRector

Deprecated random generator methods in GeneralUtility

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RandomMethodsToRandomClassRector`](../src/Rector/v8/v0/RandomMethodsToRandomClassRector.php)

```diff
+use TYPO3\CMS\Core\Crypto\Random;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-$randomBytes = GeneralUtility::generateRandomBytes();
-$randomHex = GeneralUtility::getRandomHexString();
+$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes();
+$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString();
```

<br>

## RefactorArrayBrowserWrapValueRector

Migrate the method `ArrayBrowser->wrapValue()` to use `htmlspecialchars()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorArrayBrowserWrapValueRector`](../src/Rector/v8/v7/RefactorArrayBrowserWrapValueRector.php)

```diff
 $arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
-$arrayBrowser->wrapValue('value');
+htmlspecialchars('value');
```

<br>

## RefactorBackendUtilityGetPagesTSconfigRector

Refactor method getPagesTSconfig of class BackendUtility if possible

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector`](../src/Rector/v9/v0/RefactorBackendUtilityGetPagesTSconfigRector.php)

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
-$pagesTsConfig = BackendUtility::getPagesTSconfig(1, $rootLine = null, $returnPartArray = true);
+$pagesTsConfig = BackendUtility::getRawPagesTSconfig(1, $rootLine = null);
```

<br>

## RefactorCHashArrayOfTSFERector

Refactor Internal public property cHash_array

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\typo3\RefactorCHashArrayOfTSFERector`](../src/Rector/v10/v1/typo3/RefactorCHashArrayOfTSFERector.php)

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

## RefactorDbConstantsRector

Changes TYPO3_db constants to `$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'].`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\RefactorDbConstantsRector`](../src/Rector/v8/v1/RefactorDbConstantsRector.php)

```diff
-$database = TYPO3_db;
-$username = TYPO3_db_username;
-$password = TYPO3_db_password;
-$host = TYPO3_db_host;
+$database = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'];
+$username = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'];
+$password = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'];
+$host = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'];
```

<br>

## RefactorDeprecatedConcatenateMethodsPageRendererRector

Turns method call names to new ones.

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector`](../src/Rector/v9/v4/RefactorDeprecatedConcatenateMethodsPageRendererRector.php)

```diff
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$files = $someObject->getConcatenateFiles();
+$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
```

<br>

## RefactorDeprecationLogRector

Refactor GeneralUtility deprecationLog methods

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector`](../src/Rector/v9/v0/RefactorDeprecationLogRector.php)

```diff
-GeneralUtility::logDeprecatedFunction();
-GeneralUtility::logDeprecatedViewHelperAttribute();
-GeneralUtility::deprecationLog('Message');
-GeneralUtility::getDeprecationLogFileName();
+trigger_error('A useful message', E_USER_DEPRECATED);
```

<br>

## RefactorExplodeUrl2ArrayFromGeneralUtilityRector

Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector`](../src/Rector/v9/v4/RefactorExplodeUrl2ArrayFromGeneralUtilityRector.php)

```diff
-$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
-$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
+parse_str('https://www.domain.com', $variable);
+$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
```

<br>

## RefactorIdnaEncodeMethodToNativeFunctionRector

Use native function idn_to_ascii instead of GeneralUtility::idnaEncode

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector`](../src/Rector/v10/v0/RefactorIdnaEncodeMethodToNativeFunctionRector.php)

```diff
-$domain = GeneralUtility::idnaEncode('domain.com');
-$email = GeneralUtility::idnaEncode('email@domain.com');
+$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
+$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
```

<br>

## RefactorInternalPropertiesOfTSFERector

Refactor Internal public TSFE properties

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector`](../src/Rector/v10/v1/RefactorInternalPropertiesOfTSFERector.php)

```diff
-$domainStartPage = $GLOBALS['TSFE']->domainStartPage;
+$cHash = $GLOBALS['REQUEST']->getAttribute('routing')->getArguments()['cHash'];
```

<br>

## RefactorMethodFileContentRector

Refactor method fileContent of class TemplateService

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\RefactorMethodFileContentRector`](../src/Rector/v8/v3/RefactorMethodFileContentRector.php)

```diff
-$content = $GLOBALS['TSFE']->tmpl->fileContent('foo.txt');
+$content = $GLOBALS['TSFE']->tmpl->getFileName('foo.txt') ? file_get_contents('foo.txt') : null;
```

<br>

## RefactorMethodsFromExtensionManagementUtilityRector

Refactor deprecated methods from ExtensionManagementUtility.

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector`](../src/Rector/v9/v0/RefactorMethodsFromExtensionManagementUtilityRector.php)

```diff
-ExtensionManagementUtility::removeCacheFiles();
+GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
```

<br>

## RefactorPrintContentMethodsRector

Refactor printContent methods of classes TaskModuleController and PageLayoutController

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorPrintContentMethodsRector`](../src/Rector/v8/v7/RefactorPrintContentMethodsRector.php)

```diff
 use TYPO3\CMS\Backend\Controller\PageLayoutController;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Taskcenter\Controller\TaskModuleController;

 $pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
-$pageLayoutController->printContent();
+echo $pageLayoutController->getModuleTemplate()->renderContent();

 $taskLayoutController = GeneralUtility::makeInstance(TaskModuleController::class);
-$taskLayoutController->printContent();
+echo $taskLayoutController->content;
```

<br>

## RefactorPropertiesOfTypoScriptFrontendControllerRector

Refactor some properties of TypoScriptFrontendController

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector`](../src/Rector/v9/v5/RefactorPropertiesOfTypoScriptFrontendControllerRector.php)

```diff
-$previewBeUserUid = $GLOBALS['TSFE']->ADMCMD_preview_BEUSER_uid;
-$workspacePreview = $GLOBALS['TSFE']->workspacePreview;
-$loginAllowedInBranch = $GLOBALS['TSFE']->loginAllowedInBranch;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Context\Context;
+
+$previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
+$workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
+$loginAllowedInBranch = $GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
```

<br>

## RefactorQueryViewTableWrapRector

Migrate the method `QueryView->tableWrap()` to use pre-Tag

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\RefactorQueryViewTableWrapRector`](../src/Rector/v8/v3/RefactorQueryViewTableWrapRector.php)

```diff
 $queryView = GeneralUtility::makeInstance(QueryView::class);
-$output = $queryView->tableWrap('value');
+$output = '<pre>' . 'value' . '</pre>';
```

<br>

## RefactorRemovedMarkerMethodsFromContentObjectRendererRector

Refactor removed Marker-related methods from ContentObjectRenderer.

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorRemovedMarkerMethodsFromContentObjectRendererRector`](../src/Rector/v8/v7/RefactorRemovedMarkerMethodsFromContentObjectRendererRector.php)

```diff
-$template = $this->cObj->getSubpart($this->config['templateFile'], '###TEMPLATE###');
-$html = $this->cObj->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
-$html2 = $this->cObj->substituteSubpartArray($html2, []);
-$content .= $this->cObj->substituteMarker($content, $marker, $markContent);
-$content .= $this->cObj->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
-$content .= $this->cObj->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
-$content .= $this->cObj->substituteMarkerInObject($tree, $markContentArray);
-$content .= $this->cObj->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
-$content .= $this->cObj->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC);
+use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+
+$template = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->getSubpart($this->config['templateFile'], '###TEMPLATE###');
+$html = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
+$html2 = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpartArray($html2, []);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarker($content, $marker, $markContent);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerInObject($tree, $markContentArray);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
+$content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC, !empty($GLOBALS['TSFE']->xhtmlDoctype));
```

<br>

## RefactorRemovedMarkerMethodsFromHtmlParserRector

Refactor removed Marker-related methods from HtmlParser.

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMarkerMethodsFromHtmlParserRector`](../src/Rector/v8/v0/RefactorRemovedMarkerMethodsFromHtmlParserRector.php)

```diff
 use TYPO3\CMS\Core\Html\HtmlParser;

 final class HtmlParserMarkerRendererMethods
 {
     public function doSomething(): void
     {
         $template = '';
         $markerArray = [];
         $subpartArray = [];
         $htmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(HtmlParser::class);
-        $template = $htmlparser->getSubpart($this->config['templateFile'], '###TEMPLATE###');
-        $html = $htmlparser->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
-        $html2 = $htmlparser->substituteSubpartArray($html2, []);
+        $template = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->getSubpart($this->config['templateFile'], '###TEMPLATE###');
+        $html = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
+        $html2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteSubpartArray($html2, []);

-        $html3 = $htmlparser->processTag($value, $conf, $endTag, $protected = 0);
-        $html4 = $htmlparser->processContent($value, $dir, $conf);
-
-        $content = $htmlparser->substituteMarker($content, $marker, $markContent);
-        $content .= $htmlparser->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
-        $content .= $htmlparser->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
-        $content = $htmlparser->XHTML_clean($content);
+        $content = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarker($content, $marker, $markContent);
+        $content .= \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
+        $content .= \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\MarkerBasedTemplateService::class)->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
+        $content = $htmlparser->HTMLcleaner($content);
     }
 }
```

<br>

## RefactorRemovedMethodsFromContentObjectRendererRector

Refactor removed methods from ContentObjectRenderer.

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRendererRector`](../src/Rector/v8/v0/RefactorRemovedMethodsFromContentObjectRendererRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
 $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
```

<br>

## RefactorRemovedMethodsFromGeneralUtilityRector

Refactor removed methods from GeneralUtility.

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromGeneralUtilityRector`](../src/Rector/v8/v0/RefactorRemovedMethodsFromGeneralUtilityRector.php)

```diff
-GeneralUtility::gif_compress();
+TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
```

<br>

## RefactorTCARector

A lot of different TCA changes

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\RefactorTCARector`](../src/Rector/v8/v6/RefactorTCARector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'text_17' => [
             'label' => 'text_17',
             'config' => [
                 'type' => 'text',
                 'cols' => '40',
                 'rows' => '5',
-                'wizards' => [
-                    'table' => [
-                        'notNewRecords' => 1,
-                        'type' => 'script',
-                        'title' => 'LLL:EXT:cms/locallang_ttc.xlf:bodytext.W.table',
-                        'icon' => 'content-table',
-                        'module' => [
-                            'name' => 'wizard_table'
-                        ],
-                        'params' => [
-                            'xmlOutput' => 0
-                        ]
-                    ],
-                ],
+                'renderType' => 'textTable',
             ],
         ],
     ],
 ];
```

<br>

## RefactorTsConfigRelatedMethodsRector

Refactor TSconfig related methods

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\RefactorTsConfigRelatedMethodsRector`](../src/Rector/v9/v3/RefactorTsConfigRelatedMethodsRector.php)

```diff
-$hasFilterBox = !$GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.hideFilter');
+$hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
```

<br>

## RefactorTypeInternalTypeFileAndFileReferenceToFalRector

Move TCA type group internal_type file and file_reference to FAL configuration

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RefactorTypeInternalTypeFileAndFileReferenceToFalRector`](../src/Rector/v9/v5/RefactorTypeInternalTypeFileAndFileReferenceToFalRector.php)

```diff
 return [
             'ctrl' => [],
             'columns' => [
                 'foobar_image' => [
                     'exclude' => 1,
                     'label' => 'FoobarLabel',
-                    'config' => [
-                        'type' => 'group',
-                        'internal_type' => 'file',
-                        'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
-                        'max_size' => '20000',
-                        'uploadfolder' => 'fileadmin/foobar',
-                        'maxitems' => '1',
-                    ],
+                    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
+                        'foobar_image',
+                        [
+                            'max_size' => '20000',
+                            'uploadfolder' => 'fileadmin/foobar',
+                            'maxitems' => 1,
+                            'appearance' => [
+                                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
+                            ],
+                        ],
+                        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
+                    ),
                 ],
             ],
         ];
```

<br>

## RefactorVariousGeneralUtilityMethodsRector

Refactor various deprecated methods of class GeneralUtility

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\RefactorVariousGeneralUtilityMethodsRector`](../src/Rector/v8/v1/RefactorVariousGeneralUtilityMethodsRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
 $url = 'https://www.domain.com/';
-$url = GeneralUtility::rawUrlEncodeFP($url);
+$url = str_replace('%2F', '/', rawurlencode($url));
```

<br>

## RegisterExtbaseTypeConvertersAsServicesRector

Register extbase type converters as services

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RegisterExtbaseTypeConvertersAsServicesRector`](../src/Rector/v12/v0/typo3/RegisterExtbaseTypeConvertersAsServicesRector.php)

```diff
-\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
-    MySpecialTypeConverter::class
-);
+// Remove node and add or modify existing Services.yaml in Configuration/Services.yaml
```

<br>

## RegisterIconToIconFileRector

Generate or add registerIcon calls to Icons.php file

- class: [`Ssch\TYPO3Rector\Rector\v11\v4\RegisterIconToIconFileRector`](../src/Rector/v11/v4/RegisterIconToIconFileRector.php)

```diff
 use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
 use TYPO3\CMS\Core\Imaging\IconRegistry;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
-$iconRegistry->registerIcon(
-    'mybitmapicon',
-    BitmapIconProvider::class,
-    [
-        'source' => 'EXT:my_extension/Resources/Public/Icons/mybitmap.png',
-    ]
-);
+
+// Add Icons.php file
```

<br>

## RegisterPluginWithVendorNameRector

Remove vendor name from registerPlugin call

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\RegisterPluginWithVendorNameRector`](../src/Rector/v10/v1/RegisterPluginWithVendorNameRector.php)

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

## RemoveAddQueryStringMethodRector

Remove TypoScript option addQueryString.method

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\RemoveAddQueryStringMethodRector`](../src/Rector/v11/v0/RemoveAddQueryStringMethodRector.php)

```diff
 $this->uriBuilder->setUseCacheHash(true)
     ->setCreateAbsoluteUri(true)
     ->setAddQueryString(true)
-    ->setAddQueryStringMethod('GET')
     ->build();
```

<br>

## RemoveBackendUtilityViewOnClickUsageRector

Resolve usages of BackendUtility::viewOnClick to new method

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\RemoveBackendUtilityViewOnClickUsageRector`](../src/Rector/v11/v3/RemoveBackendUtilityViewOnClickUsageRector.php)

```diff
-$onclick = BackendUtility::viewOnClick(
-    $pageId, $backPath, $rootLine, $section,
-    $viewUri, $getVars, $switchFocus
-);
+$onclick = PreviewUriBuilder::create($pageId, $viewUri)
+    ->withRootLine($rootLine)
+    ->withSection($section)
+    ->withAdditionalQueryParameters($getVars)
+    ->buildDispatcherDataAttributes([
+        PreviewUriBuilder::OPTION_SWITCH_FOCUS => $switchFocus,
+    ]);
```

<br>

## RemoveCharsetConverterParametersRector

Remove CharsetConvertParameters

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveCharsetConverterParametersRector`](../src/Rector/v8/v0/RemoveCharsetConverterParametersRector.php)

```diff
 $charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
-$charsetConvert->entities_to_utf8('string', false);
-$charsetConvert->utf8_to_numberarray('string', false, false);
+$charsetConvert->entities_to_utf8('string');
+$charsetConvert->utf8_to_numberarray('string');
```

<br>

## RemoveColPosParameterRector

Remove parameter `$colPos` from methods.

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\RemoveColPosParameterRector`](../src/Rector/v9/v3/RemoveColPosParameterRector.php)

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
```

<br>

## RemoveConfigDoctypeSwitchRector

Remove config.doctypeSwitch

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveConfigDoctypeSwitchRector`](../src/Rector/v12/v0/typoscript/RemoveConfigDoctypeSwitchRector.php)

```diff
-config.doctypeSwitch = 1
+-
```

<br>

## RemoveConfigMaxFromInputDateTimeFieldsRector

Remove TCA config 'max' on inputDateTime fields

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RemoveConfigMaxFromInputDateTimeFieldsRector`](../src/Rector/v8/v7/RemoveConfigMaxFromInputDateTimeFieldsRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'date' => [
             'exclude' => false,
             'label' => 'Date',
             'config' => [
                 'renderType' => 'inputDateTime',
-                'max' => 1,
             ],
         ],
     ],
 ];
```

<br>

## RemoveCruserIdRector

Remove the TCA option cruser_id

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveCruserIdRector`](../src/Rector/v12/v0/tca/RemoveCruserIdRector.php)

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

## RemoveDefaultInternalTypeDBRector

Remove the default type for internal_type

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\RemoveDefaultInternalTypeDBRector`](../src/Rector/v11/v5/RemoveDefaultInternalTypeDBRector.php)

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

## RemoveDisableCharsetHeaderConfigTypoScriptRector

Remove config.disableCharsetHeader

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v12\v0\RemoveDisableCharsetHeaderConfigTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v12/v0/RemoveDisableCharsetHeaderConfigTypoScriptRector.php)

```diff
-config.disableCharsetHeader = true
+-
```

<br>

## RemoveDisablePageExternalUrlOptionRector

Remove config.disablePageExternalUrl

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveDisablePageExternalUrlOptionRector`](../src/Rector/v12/v0/typoscript/RemoveDisablePageExternalUrlOptionRector.php)

```diff
-config.disablePageExternalUrl = 1
+-
```

<br>

## RemoveDivider2TabsConfigurationRector

Removed dividers2tabs functionality

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\RemoveDivider2TabsConfigurationRector`](../src/Rector/v7/v0/RemoveDivider2TabsConfigurationRector.php)

```diff
 return [
     'ctrl' => [
-        'dividers2tabs' => true,
         'label' => 'complete_identifier',
         'tstamp' => 'tstamp',
         'crdate' => 'crdate',
     ],
     'columns' => [
     ],
 ];
```

<br>

## RemoveElementTceFormsRector

Remove TCEForms key from all elements in data structure

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\flexform\RemoveElementTceFormsRector`](../src/Rector/v12/v0/flexform/RemoveElementTceFormsRector.php)

```diff
 <T3DataStructure>
     <ROOT>
-        <TCEforms>
-            <sheetTitle>aTitle</sheetTitle>
-        </TCEforms>
+        <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <aFlexField>
-                <TCEforms>
-                    <label>aFlexFieldLabel</label>
-                    <config>
-                        <type>input</type>
-                    </config>
-                </TCEforms>
+                <label>aFlexFieldLabel</label>
+                <config>
+                    <type>input</type>
+                </config>
             </aFlexField>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## RemoveElementTceFormsYamlRector

Remove TCEForms key from all elements in data structure

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\yaml\RemoveElementTceFormsYamlRector`](../src/Rector/v12/v0/yaml/RemoveElementTceFormsYamlRector.php)

```diff
 TYPO3:
   CMS:
     Form:
       prototypes:
         standard:
           finishersDefinition:
             EmailToReceiver:
               FormEngine:
                 elements:
                   recipients:
                     el:
                       _arrayContainer:
                         el:
                           email:
-                            TCEforms:
-                              label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
-                              config:
-                                type: input
+                            label: tt_content.finishersDefinition.EmailToSender.recipients.email.label
+                            config:
+                              type: input
```

<br>

## RemoveEnableMultiSelectFilterTextfieldRector

Remove "enableMultiSelectFilterTextfield" => true as its default

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\RemoveEnableMultiSelectFilterTextfieldRector`](../src/Rector/v10/v1/RemoveEnableMultiSelectFilterTextfieldRector.php)

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

## RemoveExcludeOnTransOrigPointerFieldRector

transOrigPointerField is not longer allowed to be excluded

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\RemoveExcludeOnTransOrigPointerFieldRector`](../src/Rector/v10/v3/RemoveExcludeOnTransOrigPointerFieldRector.php)

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

## RemoveFlushCachesRector

Remove `@flushesCaches` annotation

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveFlushCachesRector`](../src/Rector/v9/v5/RemoveFlushCachesRector.php)

```diff
 /**
- * My command
- *
- * @flushesCaches
+ * My Command
  */
 public function myCommand()
 {
 }
```

<br>

## RemoveFormatConstantsEmailFinisherRector

Remove constants FORMAT_PLAINTEXT and FORMAT_HTML of class `TYPO3\CMS\Form\Domain\Finishers\EmailFinisher`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector`](../src/Rector/v10/v0/RemoveFormatConstantsEmailFinisherRector.php)

```diff
-$this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
+$this->setOption('addHtmlPart', true);
```

<br>

## RemoveIconOptionForRenderTypeSelectRector

TCA icon options have been removed

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\RemoveIconOptionForRenderTypeSelectRector`](../src/Rector/v7/v6/RemoveIconOptionForRenderTypeSelectRector.php)

```diff
 return [
     'columns' => [
         'foo' => [
             'config' => [
                 'type' => 'select',
                 'renderType' => 'selectSingle',
-                'noIconsBelowSelect' => false,
+                'showIconTable' => true,
             ],
         ],
     ],
 ];
```

<br>

## RemoveIconsInOptionTagsRector

Select option iconsInOptionTags removed

- class: [`Ssch\TYPO3Rector\Rector\v7\v5\RemoveIconsInOptionTagsRector`](../src/Rector/v7/v5/RemoveIconsInOptionTagsRector.php)

```diff
 return [
     'columns' => [
         'foo' => [
             'label' => 'Label',
             'config' => [
                 'type' => 'select',
                 'maxitems' => 25,
                 'autoSizeMax' => 10,
-                'iconsInOptionTags' => 1,
             ],
         ],
     ],
 ];
```

<br>

## RemoveInitMethodFromPageRepositoryRector

Remove method call init from PageRepository

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveInitMethodFromPageRepositoryRector`](../src/Rector/v9/v5/RemoveInitMethodFromPageRepositoryRector.php)

```diff
-$repository = GeneralUtility::makeInstance(PageRepository::class);
-$repository->init(true);
+$repository = GeneralUtility::makeInstance(PageRepository::class);
```

<br>

## RemoveInitMethodGraphicalFunctionsRector

Remove method call init of class GraphicalFunctions

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodGraphicalFunctionsRector`](../src/Rector/v9/v4/RemoveInitMethodGraphicalFunctionsRector.php)

```diff
 use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
-$graphicalFunctions->init();
+$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
```

<br>

## RemoveInitMethodTemplateServiceRector

Remove method call init of class TemplateService

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodTemplateServiceRector`](../src/Rector/v9/v4/RemoveInitMethodTemplateServiceRector.php)

```diff
 use TYPO3\CMS\Core\TypoScript\TemplateService;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$templateService = GeneralUtility::makeInstance(TemplateService::class);
-$templateService->init();
+$templateService = GeneralUtility::makeInstance(TemplateService::class);
```

<br>

## RemoveInitTemplateMethodCallRector

Remove method call initTemplate from TypoScriptFrontendController

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitTemplateMethodCallRector`](../src/Rector/v9/v4/RemoveInitTemplateMethodCallRector.php)

```diff
-$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->initTemplate();
+$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
```

<br>

## RemoveInternalAnnotationRector

Remove `@internal` annotation from classes extending `\TYPO3\CMS\Extbase\Mvc\Controller\CommandController`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveInternalAnnotationRector`](../src/Rector/v9/v5/RemoveInternalAnnotationRector.php)

```diff
-/**
- * @internal
- */
 class MyCommandController extends CommandController
 {
 }
```

<br>

## RemoveL10nModeNoCopyRector

Remove l10n_mode noCopy

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\RemoveL10nModeNoCopyRector`](../src/Rector/v8/v6/RemoveL10nModeNoCopyRector.php)

```diff
 return [
     'ctrl' => [],
     'columns' => [
         'foo' => [
             'exclude' => 1,
-            'l10n_mode' => 'mergeIfNotBlank',
             'label' => 'Bar',
+            'config' => [
+                'behaviour' => [
+                    'allowLanguageSynchronization' => true
+                ]
+            ],
         ],
     ],
 ];
```

<br>

## RemoveLangCsConvObjAndParserFactoryRector

Remove CsConvObj and ParserFactory from LanguageService::class and `$GLOBALS['lang']`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveLangCsConvObjAndParserFactoryRector`](../src/Rector/v8/v0/RemoveLangCsConvObjAndParserFactoryRector.php)

```diff
 $languageService = GeneralUtility::makeInstance(LanguageService::class);
-$charsetConverter = $languageService->csConvObj;
-$Localization = $languageService->parserFactory();
-$charsetConverterGlobals = $GLOBALS['LANG']->csConvObj;
-$LocalizationGlobals = $GLOBALS['LANG']->parserFactory();
+$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
+$Localization = GeneralUtility::makeInstance(LocalizationFactory::class);
+$charsetConverterGlobals = GeneralUtility::makeInstance(CharsetConverter::class);
+$LocalizationGlobals = GeneralUtility::makeInstance(LocalizationFactory::class);
```

<br>

## RemoveLanguageModeMethodsFromTypo3QuerySettingsRector

Remove language mode methods from class Typo3QuerySettings

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\RemoveLanguageModeMethodsFromTypo3QuerySettingsRector`](../src/Rector/v11/v0/RemoveLanguageModeMethodsFromTypo3QuerySettingsRector.php)

```diff
 use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

 $querySettings = new Typo3QuerySettings();
-$querySettings->setLanguageUid(0)->setLanguageMode()->getLanguageMode();
+$querySettings->setLanguageUid(0);
```

<br>

## RemoveLocalizationModeKeepIfNeededRector

Remove localizationMode keep if allowLanguageSynchronization is enabled

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RemoveLocalizationModeKeepIfNeededRector`](../src/Rector/v8/v7/RemoveLocalizationModeKeepIfNeededRector.php)

```diff
 return [
     'columns' => [
         'foo' => [
             'label' => 'Bar',
             'config' => [
                 'type' => 'inline',
                 'appearance' => [
                     'behaviour' => [
-                        'localizationMode' => 'keep',
                         'allowLanguageSynchronization' => true,
                     ],
                 ],
             ],
         ],
     ],
 ];
```

<br>

## RemoveMailerAdapterInterfaceRector

Refactor AdditionalFieldProvider classes

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveMailerAdapterInterfaceRector`](../src/Rector/v12/v0/typo3/RemoveMailerAdapterInterfaceRector.php)

```diff
-class RemoveMailerAdapterInterfaceFixture implements TYPO3\CMS\Mail\MailerAdapterInterface
+class RemoveMailerAdapterInterfaceFixture
```

<br>

## RemoveMetaCharSetRector

Remove config.metaCharset

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveMetaCharSetRector`](../src/Rector/v12/v0/typoscript/RemoveMetaCharSetRector.php)

```diff
-config.metaCharset = utf-8
+-
```

<br>

## RemoveMethodCallConnectDbRector

Remove `EidUtility::connectDB()` call

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallConnectDbRector`](../src/Rector/v7/v0/RemoveMethodCallConnectDbRector.php)

```diff
-EidUtility::connectDB()
+-
```

<br>

## RemoveMethodCallLoadTcaRector

Remove `GeneralUtility::loadTCA()` call

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallLoadTcaRector`](../src/Rector/v7/v0/RemoveMethodCallLoadTcaRector.php)

```diff
-'GeneralUtility::loadTCA()'
+-
```

<br>

## RemoveMethodInitTCARector

Remove superfluous EidUtility::initTCA call

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector`](../src/Rector/v9/v0/RemoveMethodInitTCARector.php)

```diff
-use TYPO3\CMS\Frontend\Utility\EidUtility;
-
-EidUtility::initTCA();
+-
```

<br>

## RemoveMethodsFromEidUtilityAndTsfeRector

Remove EidUtility and various TSFE methods

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveMethodsFromEidUtilityAndTsfeRector`](../src/Rector/v9/v4/RemoveMethodsFromEidUtilityAndTsfeRector.php)

```diff
-use TYPO3\CMS\Frontend\Utility\EidUtility;
-
-EidUtility::initExtensionTCA('foo');
-EidUtility::initFeUser();
-EidUtility::initLanguage();
-EidUtility::initTCA();
+-
```

<br>

## RemoveNewContentElementWizardOptionsRector

Remove TSConfig mod.web_layout.disableNewContentElementWizard and mod.newContentElementWizard.override

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveNewContentElementWizardOptionsRector`](../src/Rector/v12/v0/typoscript/RemoveNewContentElementWizardOptionsRector.php)

```diff
-mod.web_layout.disableNewContentElementWizard = 1
-mod.newContentElementWizard.override = 1
+-
```

<br>

## RemoveNoCacheHashAndUseCacheHashAttributeFluidRector

Remove noCacheHash="1" and useCacheHash="1" attribute

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\v10\v0\RemoveNoCacheHashAndUseCacheHashAttributeFluidRector`](../src/FileProcessor/Fluid/Rector/v10/v0/RemoveNoCacheHashAndUseCacheHashAttributeFluidRector.php)

```diff
-<f:link.page noCacheHash="1">Link</f:link.page>
-<f:link.typolink useCacheHash="1">Link</f:link.typolink>
+<f:link.page>Link</f:link.page>
+<f:link.typolink>Link</f:link.typolink>
```

<br>

## RemoveOptionLocalizeChildrenAtParentLocalizationRector

Remove option localizeChildrenAtParentLocalization

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveOptionLocalizeChildrenAtParentLocalizationRector`](../src/Rector/v9/v0/RemoveOptionLocalizeChildrenAtParentLocalizationRector.php)

```diff
 return [
     'ctrl' => [],
     'columns' => [
         'foo' => [
             'config' =>
                 [
                     'type' => 'inline',
-                    'behaviour' => [
-                        'localizeChildrenAtParentLocalization' => '1',
-                    ],
+                    'behaviour' => [],
                 ],
         ],
     ],
 ];
```

<br>

## RemoveOptionShowIfRteRector

Dropped TCA option showIfRTE in type=check

- class: [`Ssch\TYPO3Rector\Rector\v8\v4\RemoveOptionShowIfRteRector`](../src/Rector/v8/v4/RemoveOptionShowIfRteRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'rte_enabled' => [
             'exclude' => 1,
             'label' => 'LLL:EXT:lang/locallang_general.php:LGL.disableRTE',
             'config' => [
                 'type' => 'check',
-                'showIfRTE' => 1
             ]
         ],
     ],
 ];
```

<br>

## RemoveOptionVersioningFollowPagesRector

TCA option versioning_followPages removed

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\RemoveOptionVersioningFollowPagesRector`](../src/Rector/v8/v5/RemoveOptionVersioningFollowPagesRector.php)

```diff
 return [
     'ctrl' => [
-        'versioningWS' => 2,
-        'versioning_followPages' => TRUE,
+        'versioningWS' => true,
     ],
     'columns' => [
     ]
 ];
```

<br>

## RemovePropertiesFromSimpleDataHandlerControllerRector

Remove assignments or accessing of properties prErr and uPT from class SimpleDataHandlerController

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector`](../src/Rector/v9/v0/RemovePropertiesFromSimpleDataHandlerControllerRector.php)

```diff
 final class MySimpleDataHandlerController extends SimpleDataHandlerController
 {
     public function myMethod()
     {
-        $pErr = $this->prErr;
-        $this->prErr = true;
-        $this->uPT = true;
     }
 }
```

<br>

## RemovePropertyExtensionNameRector

Use method getControllerExtensionName from `$request` property instead of removed property `$extensionName`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector`](../src/Rector/v10/v0/RemovePropertyExtensionNameRector.php)

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

## RemovePropertyUserAuthenticationRector

Use method getBackendUserAuthentication instead of removed property `$userAuthentication`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemovePropertyUserAuthenticationRector`](../src/Rector/v8/v0/RemovePropertyUserAuthenticationRector.php)

```diff
 class MyCommandController extends CommandController
 {
     public function myMethod()
     {
-        if ($this->userAuthentication !== null) {
+        if ($this->getBackendUserAuthentication() !== null) {

         }
     }
 }
```

<br>

## RemoveRedundantFeLoginModeMethodsRector

Remove redundant methods that are used to handle fe_login_mode

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveRedundantFeLoginModeMethodsRector`](../src/Rector/v12/v0/typo3/RemoveRedundantFeLoginModeMethodsRector.php)

```diff
-\TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication->hideActiveLogin();
+-
```

<br>

## RemoveRelativeToCurrentScriptArgumentsRector

Removes all usages of the relativeToCurrentScript parameter

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector`](../src/Rector/v12/v0/typo3/RemoveRelativeToCurrentScriptArgumentsRector.php)

```diff
 /** @var AudioTagRenderer $audioTagRenderer */
 $audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
-$foo = $audioTagRenderer->render($file, $width, $height, $options, $relative);
+$foo = $audioTagRenderer->render($file, $width, $height, $options);
```

<br>

## RemoveRteHtmlParserEvalWriteFileRector

remove evalWriteFile method from RteHtmlparser.

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveRteHtmlParserEvalWriteFileRector`](../src/Rector/v8/v0/RemoveRteHtmlParserEvalWriteFileRector.php)

```diff
 use TYPO3\CMS\Core\Html\RteHtmlParser;

 final class RteHtmlParserRemovedMethods
 {
     public function doSomething(): void
     {
         $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
-        $rtehtmlparser->evalWriteFile();
     }
 }
```

<br>

## RemoveSecondArgumentGeneralUtilityMkdirDeepRector

Remove second argument of `GeneralUtility::mkdir_deep()`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector`](../src/Rector/v9/v0/RemoveSecondArgumentGeneralUtilityMkdirDeepRector.php)

```diff
-GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
```

<br>

## RemoveSeliconFieldPathRector

TCA option "selicon_field_path" removed

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemoveSeliconFieldPathRector`](../src/Rector/v10/v0/RemoveSeliconFieldPathRector.php)

```diff
 return [
     'ctrl' => [
         'selicon_field' => 'icon',
-        'selicon_field_path' => 'uploads/media'
     ],
 ];
```

<br>

## RemoveSendCacheHeadersConfigOptionRector

Remove config.sendCacheHeaders_onlyWhenLoginDeniedInBranch

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSendCacheHeadersConfigOptionRector`](../src/Rector/v12/v0/typoscript/RemoveSendCacheHeadersConfigOptionRector.php)

```diff
-config.sendCacheHeaders_onlyWhenLoginDeniedInBranch
+-
```

<br>

## RemoveShowRecordFieldListInsideInterfaceSectionRector

Remove showRecordFieldList inside section interface

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\RemoveShowRecordFieldListInsideInterfaceSectionRector`](../src/Rector/v10/v3/RemoveShowRecordFieldListInsideInterfaceSectionRector.php)

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

## RemoveSpamProtectEmailAddressesAsciiOptionRector

Remove config.spamProtectEmailAddresses with option ascii

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveSpamProtectEmailAddressesAsciiOptionRector`](../src/Rector/v12/v0/typoscript/RemoveSpamProtectEmailAddressesAsciiOptionRector.php)

```diff
-config.spamProtectEmailAddresses = ascii
+-
```

<br>

## RemoveSupportForTransForeignTableRector

Remove support for transForeignTable in TCA

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\RemoveSupportForTransForeignTableRector`](../src/Rector/v8/v5/RemoveSupportForTransForeignTableRector.php)

```diff
 return [
-    'ctrl' => [
-        'transForeignTable' => 'l10n_parent',
-        'transOrigPointerTable' => 'l10n_parent',
-    ],
+    'ctrl' => [],
 ];
```

<br>

## RemoveTCAInterfaceAlwaysDescriptionRector

Remove ['interface']['always_description']

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTCAInterfaceAlwaysDescriptionRector`](../src/Rector/v12/v0/tca/RemoveTCAInterfaceAlwaysDescriptionRector.php)

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

## RemoveTSConfigModesRector

Remove TSConfig options.workspaces.swapMode and options.workspaces.changeStageMode

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RemoveTSConfigModesRector`](../src/Rector/v12/v0/typoscript/RemoveTSConfigModesRector.php)

```diff
-options.workspaces.swapMode = any
-options.workspaces.changeStageMode = any
+-
```

<br>

## RemoveTSFEConvOutputCharsetCallsRector

Removes usages of TSFE->convOutputCharset(...)

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveTSFEConvOutputCharsetCallsRector`](../src/Rector/v12/v0/typo3/RemoveTSFEConvOutputCharsetCallsRector.php)

```diff
 $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$foo = $GLOBALS['TSFE']->convOutputCharset($content);
-$bar = $tsfe->convOutputCharset('content');
+$foo = $content;
+$bar = 'content';
```

<br>

## RemoveTSFEMetaCharSetCallsRector

Removes calls to metaCharset property or methods of TSFE

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveTSFEMetaCharSetCallsRector`](../src/Rector/v12/v0/typo3/RemoveTSFEMetaCharSetCallsRector.php)

```diff
 $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$foo = $GLOBALS['TSFE']->metaCharset;
-$bar = $tsfe->metaCharset;
+$foo = 'utf-8';
+$bar = 'utf-8';
```

<br>

## RemoveTableLocalPropertyRector

Remove TCA property table_local in foreign_match_fields

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\tca\RemoveTableLocalPropertyRector`](../src/Rector/v12/v0/tca/RemoveTableLocalPropertyRector.php)

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

## RemoveTcaOptionSetToDefaultOnCopyRector

TCA option setToDefaultOnCopy removed

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemoveTcaOptionSetToDefaultOnCopyRector`](../src/Rector/v10/v0/RemoveTcaOptionSetToDefaultOnCopyRector.php)

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

## RemoveUpdateRootlineDataRector

Remove unused `TemplateService->updateRootlineData()` calls

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\RemoveUpdateRootlineDataRector`](../src/Rector/v12/v0/typo3/RemoveUpdateRootlineDataRector.php)

```diff
-$templateService = GeneralUtility::makeInstance(TemplateService::class);
-$templateService->updateRootlineData();
+$templateService = GeneralUtility::makeInstance(TemplateService::class);
```

<br>

## RemoveUseCacheHashRector

Remove useCacheHash TypoScript setting

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\typoscript\RemoveUseCacheHashRector`](../src/Rector/v10/v0/typoscript/RemoveUseCacheHashRector.php)

```diff
 typolink {
     parameter = 3
-    useCacheHash = 1
 }
```

<br>

## RemoveWakeupCallFromEntityRector

Remove __wakeup call for AbstractDomainObject

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveWakeupCallFromEntityRector`](../src/Rector/v8/v0/RemoveWakeupCallFromEntityRector.php)

```diff
 use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

 class MyWakeupCallerClass extends AbstractDomainObject
 {
     private $mySpecialResourceAfterWakeUp;

     public function __wakeup()
     {
         $this->mySpecialResourceAfterWakeUp = fopen(__FILE__, 'wb');
-        parent::__wakeup();
     }
 }
```

<br>

## RemoveWorkspacePlaceholderShadowColumnsConfigurationRector

removeWorkspacePlaceholderShadowColumnsConfiguration

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\tca\RemoveWorkspacePlaceholderShadowColumnsConfigurationRector`](../src/Rector/v11/v0/tca/RemoveWorkspacePlaceholderShadowColumnsConfigurationRector.php)

```diff
 return [
     'ctrl' => [
-        'shadowColumnsForNewPlaceholders' => '',
-        'shadowColumnsForMovePlaceholders' => '',
     ],
 ];
```

<br>

## RemovedTcaSelectTreeOptionsRector

Removed TCA tree options: width, allowRecursiveMode, autoSizeMax

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\RemovedTcaSelectTreeOptionsRector`](../src/Rector/v8/v3/RemovedTcaSelectTreeOptionsRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'categories' => [
             'config' => [
                 'type' => 'input',
                 'renderType' => 'selectTree',
-                'autoSizeMax' => 5,
+                'size' => 5,
                 'treeConfig' => [
-                    'appearance' => [
-                        'width' => 100,
-                        'allowRecursiveMode' => true
-                    ]
+                    'appearance' => []
                 ]
             ],
         ],
     ],
 ];
```

<br>

## RenameClassMapAliasRector

Replaces defined classes by new ones.

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector`](../src/Rector/Migrations/RenameClassMapAliasRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;use Ssch\TYPO3Rector\Rector\CodeQuality\Rector\General\RenameClassMapAliasRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => RenameClassMapAliasRector::class,
            'configuration' => [
                'class_alias_maps' => 'config/Migrations/Code/ClassAliasMap.php',
            ],
        ],
    ]);
};
```

↓

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

## RenameConstantsAndSetupFileEndingRector

Rename setup.txt and constants.txt to *.typoscript

- class: [`Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0\RenameConstantsAndSetupFileEndingRector`](../src/FileProcessor/Resources/Files/Rector/v12/v0/RenameConstantsAndSetupFileEndingRector.php)

```diff
-setup.txt
+setup.typoscript
```

<br>

## RenameExtTypoScriptFilesFileRector

Rename ext_typoscript_*.txt to ext_typoscript_*.typoscript

- class: [`Ssch\TYPO3Rector\FileProcessor\Resources\Files\Rector\v12\v0\RenameExtTypoScriptFilesFileRector`](../src/FileProcessor/Resources/Files/Rector/v12/v0/RenameExtTypoScriptFilesFileRector.php)

```diff
-ext_typoscript_constants.txt
+ext_typoscript_constants.typoscript
```

<br>

## RenameMailLinkHandlerKeyRector

Rename key mail to email for MailLinkHandler

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\RenameMailLinkHandlerKeyRector`](../src/Rector/v12/v0/typoscript/RenameMailLinkHandlerKeyRector.php)

```diff
 TCEMAIN.linkHandler {
-    mail {
+    email {
         handler = TYPO3\\CMS\\Recordlist\\LinkHandler\\MailLinkHandler
         label = LLL:EXT:recordlist/Resources/Private/Language/locallang_browse_links.xlf:email
         displayAfter = page,file,folder,url
         scanBefore = url
     }
 }
```

<br>

## RenameMethodCallToEnvironmentMethodCallRector

Turns method call names to new ones from new Environment API.

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\RenameMethodCallToEnvironmentMethodCallRector`](../src/Rector/v9/v2/RenameMethodCallToEnvironmentMethodCallRector.php)

```diff
-Bootstrap::usesComposerClassLoading();
-GeneralUtility::getApplicationContext();
-EnvironmentService::isEnvironmentInCliMode();
+Environment::isComposerMode();
+Environment::getContext();
+Environment::isCli();
```

<br>

## RenamePiListBrowserResultsRector

Rename pi_list_browseresults calls to renderPagination

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector`](../src/Rector/v7/v6/RenamePiListBrowserResultsRector.php)

```diff
-$this->pi_list_browseresults
+$this->renderPagination
```

<br>

## RenderCharsetDefaultsToUtf8Rector

The property `$TSFE->renderCharset` is now always set to utf-8

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RenderCharsetDefaultsToUtf8Rector`](../src/Rector/v8/v0/RenderCharsetDefaultsToUtf8Rector.php)

```diff
-mb_strlen(trim($this->gp[$this->formFieldName]), $GLOBALS['TSFE']->renderCharset) > 0;
+mb_strlen(trim($this->gp[$this->formFieldName]), 'utf-8') > 0;
```

<br>

## RenderTypeFlexFormRector

Add renderType node in FlexForm

- class: [`Ssch\TYPO3Rector\FileProcessor\FlexForms\Rector\v7\v6\RenderTypeFlexFormRector`](../src/FileProcessor/FlexForms/Rector/v7/v6/RenderTypeFlexFormRector.php)

```diff
 <T3DataStructure>
     <ROOT>
         <sheetTitle>aTitle</sheetTitle>
         <type>array</type>
         <el>
             <a_select_field>
                 <label>Select field</label>
                 <config>
                     <type>select</type>
+                    <renderType>selectSingle</renderType>
                     <items>
                         <numIndex index="0" type="array">
                             <numIndex index="0">Label</numIndex>
                         </numIndex>
                     </items>
                 </config>
             </a_select_field>
         </el>
     </ROOT>
 </T3DataStructure>
```

<br>

## ReplaceAnnotationRector

Replace old annotation by new one

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector`](../src/Rector/v9/v0/ReplaceAnnotationRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
use TYPO3\CMS\Extbase\Annotation\ORM\Transient;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => ReplaceAnnotationRector::class,
            'configuration' => [
                'old_to_new_annotations' => [
                    'transient' => Transient::class,
                ],
            ],
        ],
    ]);
};
```

↓

```diff
+use TYPO3\CMS\Extbase\Annotation as Extbase;
 /**
- * @transient
+ * @Extbase\ORM\Transient
  */
 private $someProperty;
```

<br>

## ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector

Replace usages of `ContentObjectRenderer->getMailTo()` with `EmailLinkBuilder->processEmailLink()`

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector`](../src/Rector/v12/v0/typo3/ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector.php)

```diff
-$result = $cObj->getMailTo($mailAddress, $linktxt)
+$result = GeneralUtility::makeInstance(EmailLinkBuilder::class, $cObj, $cObj->getTypoScriptFrontendController())
+    ->processEmailLink((string)$mailAddress, (string)$linktxt);
```

<br>

## ReplaceExpressionBuilderMethodsRector

Replaces ExpressionBuilder methods `orX()` & `andX()`

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceExpressionBuilderMethodsRector`](../src/Rector/v12/v0/typo3/ReplaceExpressionBuilderMethodsRector.php)

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

## ReplaceExtKeyWithExtensionKeyRector

Replace $_EXTKEY with extension key

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\ReplaceExtKeyWithExtensionKeyRector`](../src/Rector/v9/v0/ReplaceExtKeyWithExtensionKeyRector.php)

```diff
 ExtensionUtility::configurePlugin(
-    'Foo.'.$_EXTKEY,
+    'Foo.'.'bar',
     'ArticleTeaser',
     [
         'FooBar' => 'baz',
     ]
 );
```

<br>

## ReplaceExtensionPathRelativeFluidRector

Use <f:uri.resource> instead of <v:extension.path.relative>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceExtensionPathRelativeFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceExtensionPathRelativeFluidRector.php)

```diff
-{v:extension.path.relative(extensionName:'my_extension')}Resources/Public/Css/style.css
+{f:uri.resource(extensionName:'my_extension',path:'Css/style.css')}
```

<br>

## ReplaceFormatJsonEncodeFluidRector

Use <f:format.json> instead of <v:format.json.encode>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceFormatJsonEncodeFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceFormatJsonEncodeFluidRector.php)

```diff
-{someArray -> v:format.json.encode()}
+{someArray -> f:format.json()}
```

<br>

## ReplaceInjectAnnotationWithMethodRector

Turns properties with `@TYPO3\CMS\Extbase\Annotation\Inject` to setter injection

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\ReplaceInjectAnnotationWithMethodRector`](../src/Rector/v11/v0/ReplaceInjectAnnotationWithMethodRector.php)

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

## ReplaceLFluidRector

Use <f:translate> instead of <v:l>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceLFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceLFluidRector.php)

```diff
-<v:l key="my-key" extensionName="my_extension" />
-<vhs:l key="my-other-key" />
+<f:translate key="my-key" extensionName="my_extension" />
+<f:translate key="my-other-key" />
 <v:loop ...>
```

<br>

## ReplaceMediaImageFluidRector

Use <f:image> instead of <v:media.image>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceMediaImageFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceMediaImageFluidRector.php)

```diff
-<v:media.image src="{image.uid}" treatIdAsReference="true" />
+<f:image src="{image.uid}" treatIdAsReference="true" />
```

<br>

## ReplaceOrFluidRector

Use <f:or> instead of <v:or>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceOrFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceOrFluidRector.php)

```diff
-{someVariable -> v:or(alternative: 'Fallback text')}
-{v:or(content: someVariable, alternative: 'Fallback text')}
+{someVariable ?: 'Fallback text'}
+{someVariable ?: 'Fallback text'}
```

<br>

## ReplacePageRepoOverlayFunctionRector

Replace `PageRepository->getRecordOverlay()` with `->getLanguageOverlay()`

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplacePageRepoOverlayFunctionRector`](../src/Rector/v12/v0/typo3/ReplacePageRepoOverlayFunctionRector.php)

```diff
-$pageRepo->getRecordOverlay('', [], '');
+$pageRepo->getLanguageOverlay('', []);
```

<br>

## ReplacePreviewUrlMethodRector

Replace getPreviewUrl

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplacePreviewUrlMethodRector`](../src/Rector/v12/v0/typo3/ReplacePreviewUrlMethodRector.php)

```diff
-$foo = BackendUtility::getPreviewUrl(
-    $pageUid,
-    $backPath,
-    $rootLine,
-    $anchorSection,
-    $alternativeUrl,
-    $additionalGetVars,
-    &$switchFocus
-);
+$foo = (string) PreviewUriBuilder::create($pageUid)
+    ->withRootLine($rootLine)
+    ->withSection($anchorSection)
+    ->withAdditionalQueryParameters($additionalGetVars)
+    ->buildUri([
+        PreviewUriBuilder::OPTION_SWITCH_FOCUS => $switchFocus,
+    ]);
```

<br>

## ReplaceStdAuthCodeWithHmacRector

Replace GeneralUtility::stdAuthCode with GeneralUtility::hmac

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\ReplaceStdAuthCodeWithHmacRector`](../src/Rector/v11/v3/ReplaceStdAuthCodeWithHmacRector.php)

```diff
-\TYPO3\CMS\Core\Utility\GeneralUtility::stdAuthCode(5);
+// You have to migrate GeneralUtility::stdAuthCode to GeneralUtility::hmac(). To make types work you should check the old function implementation
```

<br>

## ReplaceTSFEATagParamsCallOnGlobalsRector

Replaces all direct calls to `$GLOBALS['TSFE']->ATagParams.`

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\ReplaceTSFEATagParamsCallOnGlobalsRector`](../src/Rector/v11/v5/ReplaceTSFEATagParamsCallOnGlobalsRector.php)

```diff
-$foo = $GLOBALS['TSFE']->ATagParams;
+$foo = $GLOBALS['TSFE']->config['config']['ATagParams'] ?? '';
```

<br>

## ReplaceTSFECheckEnableFieldsRector

Replace TSFE calls to checkEnableFields with new RecordAccessVoter->accessGranted method

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFECheckEnableFieldsRector`](../src/Rector/v12/v0/typo3/ReplaceTSFECheckEnableFieldsRector.php)

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

## ReplaceTSFEWithContextMethodsRector

Replace TSFE with Context methods

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\ReplaceTSFEWithContextMethodsRector`](../src/Rector/v12/v0/typo3/ReplaceTSFEWithContextMethodsRector.php)

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

## ReplaceUriImageFluidRector

Use <f:uri.image> instead of <v:uri.image>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceUriImageFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceUriImageFluidRector.php)

```diff
-{v:uri.image(src:image.uid, treatIdAsReference: 1)}
-{v:uri.image(src:image.uid, treatIdAsReference: 1, relative: 1)}
-{v:uri.image(src:image.uid, treatIdAsReference: 1, relative: 0)}
-{v:uri.image(src:image.uid, treatIdAsReference: 1, maxW: 250, maxH: 250)}
+{f:uri.image(src:image.uid, treatIdAsReference: 1)}
+{f:uri.image(src:image.uid, treatIdAsReference: 1)}
+{f:uri.image(src:image.uid, treatIdAsReference: 1, absolute: 1)}
+{f:uri.image(src:image.uid, treatIdAsReference: 1, maxWidth: 250, maxHeight: 250)}
```

<br>

## ReplaceVariableSetFluidRector

Use <f:variable> instead of <v:variable.set>

- class: [`Ssch\TYPO3Rector\FileProcessor\Fluid\Rector\vhs\ReplaceVariableSetFluidRector`](../src/FileProcessor/Fluid/Rector/vhs/ReplaceVariableSetFluidRector.php)

```diff
-<v:variable.set name="myvariable" value="a string value" />
-{myvariable -> v:variable.set(name:'othervariable')}
+<f:variable name="myvariable" value="a string value" />
+{myvariable -> f:variable(name:'othervariable')}
```

<br>

## ReplacedGeneralUtilitySysLogWithLogginApiRector

Replaced GeneralUtility::sysLog with Logging API

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\ReplacedGeneralUtilitySysLogWithLogginApiRector`](../src/Rector/v9/v0/ReplacedGeneralUtilitySysLogWithLogginApiRector.php)

```diff
+use TYPO3\CMS\Core\Log\LogManager;
+use TYPO3\CMS\Core\Log\LogLevel;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::initSysLog();
-GeneralUtility::sysLog('message', 'foo', 0);
+GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message');
```

<br>

## RequireMethodsToNativeFunctionsRector

Refactor GeneralUtility::requireOnce and GeneralUtility::requireFile

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RequireMethodsToNativeFunctionsRector`](../src/Rector/v8/v0/RequireMethodsToNativeFunctionsRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-GeneralUtility::requireOnce('somefile.php');
-GeneralUtility::requireFile('some_other_file.php');
+require_once 'somefile.php';
+require 'some_other_file.php';
```

<br>

## RichtextFromDefaultExtrasToEnableRichtextRector

TCA richtext configuration in defaultExtras dropped

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\RichtextFromDefaultExtrasToEnableRichtextRector`](../src/Rector/v8/v6/RichtextFromDefaultExtrasToEnableRichtextRector.php)

```diff
 [
     'columns' => [
         'content' => [
             'config' => [
                 'type' => 'text',
+                'enableRichtext' => true,
             ],
-            'defaultExtras' => 'richtext:rte_transform',
         ],
     ],
 ];
```

<br>

## RteHtmlParserRector

Remove second argument of HTMLcleaner_db getKeepTags. Substitute calls for siteUrl getUrl

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RteHtmlParserRector`](../src/Rector/v8/v0/RteHtmlParserRector.php)

```diff
 use TYPO3\CMS\Core\Html\RteHtmlParser;

 $rteHtmlParser = new RteHtmlParser();
-$rteHtmlParser->HTMLcleaner_db('arg1', 'arg2');
-$rteHtmlParser->getKeepTags('arg1', 'arg2');
-$rteHtmlParser->getUrl('http://example.com');
-$rteHtmlParser->siteUrl();
+$rteHtmlParser->HTMLcleaner_db('arg1');
+$rteHtmlParser->getKeepTags('arg1');
+\TYPO3\CMS\Core\Utility\GeneralUtility::getUrl('http://example.com');
+\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
```

<br>

## SendNotifyEmailToMailApiRector

Refactor ContentObjectRenderer::sendNotifyEmail to MailMessage-API

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\SendNotifyEmailToMailApiRector`](../src/Rector/v10/v1/SendNotifyEmailToMailApiRector.php)

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

## SetSystemLocaleFromSiteLanguageRector

Refactor `TypoScriptFrontendController->settingLocale()` to `Locales::setSystemLocaleFromSiteLanguage()`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector`](../src/Rector/v10/v0/SetSystemLocaleFromSiteLanguageRector.php)

```diff
+use TYPO3\CMS\Core\Localization\Locales;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

 $controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
-$controller->settingLocale();
+Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
```

<br>

## SimplifyCheckboxItemsTCARector

Simplify checkbox items TCA

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\tca\SimplifyCheckboxItemsTCARector`](../src/Rector/v11/v5/tca/SimplifyCheckboxItemsTCARector.php)

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

## SoftReferencesFunctionalityRemovedRector

TSconfig and TStemplate soft references functionality removed

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\SoftReferencesFunctionalityRemovedRector`](../src/Rector/v8/v3/SoftReferencesFunctionalityRemovedRector.php)

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'TSconfig' => [
             'label' => 'TSconfig:',
             'config' => [
                 'type' => 'text',
                 'cols' => '40',
                 'rows' => '5',
-                'softref' => 'TSconfig',
             ],
             'defaultExtras' => 'fixed-font : enable-tab',
         ],
     ],
 ];
```

<br>

## SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector

Use method addDocuments from WriteService of SolrConnection class

- class: [`Ssch\TYPO3Rector\Rector\Extensions\solr\v8\SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector`](../src/Rector/Extensions/solr/v8/SolrConnectionAddDocumentsToWriteServiceAddDocumentsRector.php)

```diff
-$this->solrConnection->addDocuments([]);
+$this->solrConnection->getWriteService()->addDocuments([]);
```

<br>

## SolrSiteToSolrRepositoryRector

Use SiteRepository instead of instantiating class Site directly with page id

- class: [`Ssch\TYPO3Rector\Rector\Extensions\solr\v8\SolrSiteToSolrRepositoryRector`](../src/Rector/Extensions/solr/v8/SolrSiteToSolrRepositoryRector.php)

```diff
-$site1 = GeneralUtility::makeInstance(Site::class, 1);
+$site1 = GeneralUtility::makeInstance(SiteRepository::class)->getSiteByPageId(1);
```

<br>

## SubstituteBackendTemplateViewWithModuleTemplateRector

Use an instance of ModuleTemplate instead of BackendTemplateView

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\SubstituteBackendTemplateViewWithModuleTemplateRector`](../src/Rector/v11/v5/SubstituteBackendTemplateViewWithModuleTemplateRector.php)

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

## SubstituteCompositeExpressionAddMethodsRector

Replace `add()` and `addMultiple()` of CompositeExpression with `with()`

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\SubstituteCompositeExpressionAddMethodsRector`](../src/Rector/v12/v0/typo3/SubstituteCompositeExpressionAddMethodsRector.php)

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

## SubstituteConstantParsetimeStartRector

Substitute `$GLOBALS['PARSETIME_START']` with round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000)

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector`](../src/Rector/v9/v0/SubstituteConstantParsetimeStartRector.php)

```diff
-$parseTime = $GLOBALS['PARSETIME_START'];
+$parseTime = round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000);
```

<br>

## SubstituteConstantsModeAndRequestTypeRector

Substitute TYPO3_MODE and TYPO3_REQUESTTYPE constants

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector`](../src/Rector/v11/v0/SubstituteConstantsModeAndRequestTypeRector.php)

```diff
-defined('TYPO3_MODE') or die();
+defined('TYPO3') or die();
```

<br>

## SubstituteEnvironmentServiceWithApplicationTypeRector

Substitute class EnvironmentService with ApplicationType class\"

- class: [`Ssch\TYPO3Rector\Rector\v11\v2\typo3\SubstituteEnvironmentServiceWithApplicationTypeRector`](../src/Rector/v11/v2/typo3/SubstituteEnvironmentServiceWithApplicationTypeRector.php)

```diff
-if($this->environmentService->isEnvironmentInFrontendMode()) {
+if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend())
     ...
 }
```

<br>

## SubstituteExtbaseRequestGetBaseUriRector

Use PSR-7 compatible request for uri instead of the method getBaseUri

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\SubstituteExtbaseRequestGetBaseUriRector`](../src/Rector/v11/v3/SubstituteExtbaseRequestGetBaseUriRector.php)

```diff
-$baseUri = $this->request->getBaseUri();
+$request = $GLOBALS['TYPO3_REQUEST'];
+/** @var NormalizedParams $normalizedParams */
+$normalizedParams = $request->getAttribute('normalizedParams');
+$baseUri = $normalizedParams->getSiteUrl();
```

<br>

## SubstituteGeneralUtilityDevLogRector

Substitute `GeneralUtility::devLog()` to Logging API

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\SubstituteGeneralUtilityDevLogRector`](../src/Rector/v9/v0/SubstituteGeneralUtilityDevLogRector.php)

```diff
+use TYPO3\CMS\Core\Log\LogLevel;
+use TYPO3\CMS\Core\Log\LogManager;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::devLog('message', 'foo', 0, $data);
+GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->log(LogLevel::INFO, 'message', $data);
```

<br>

## SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector

Substitute deprecated method calls of class GeneralUtility

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector`](../src/Rector/v10/v4/SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector.php)

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

## SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector

Use PageRenderer and IconFactory directly instead of getting them from the ModuleTemplate

- class: [`Ssch\TYPO3Rector\Rector\v11\v5\SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector`](../src/Rector/v11/v5/SubstituteGetIconFactoryAndGetPageRendererFromModuleTemplateRector.php)

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

## SubstituteMethodRmFromListOfGeneralUtilityRector

Use native php functions instead of GeneralUtility::rmFromList

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\SubstituteMethodRmFromListOfGeneralUtilityRector`](../src/Rector/v11/v3/SubstituteMethodRmFromListOfGeneralUtilityRector.php)

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

## SubstituteOldWizardIconsRector

The TCA migration migrates the icon calls to the new output if used as wizard icon

:wrench: **configure it!**

- class: [`Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector`](../src/Rector/v8/v4/SubstituteOldWizardIconsRector.php)

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Ssch\TYPO3Rector\Rector\v8\v4\SubstituteOldWizardIconsRector;

return static function (RectorConfig $rectorConfig): void {
    $containerConfigurator->extension('rectorConfig', [
        [
            'class' => SubstituteOldWizardIconsRector::class,
            'configuration' => [
                'old_to_new_file_locations' => [
                    'add.gif' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_add.gif',
                ],
            ],
        ],
    ]);
};
```

↓

```diff
 return [
     'ctrl' => [
     ],
     'columns' => [
         'bodytext' => [
             'config' => [
                 'type' => 'text',
                 'wizards' => [
                     't3editorHtml' => [
-                        'icon' => 'wizard_table.gif',
+                        'icon' => 'content-table',
                     ],
                 ],
             ],
         ],
     ],
 ];
```

<br>

## SubstituteResourceFactoryRector

Substitue `ResourceFactory::getInstance()` through GeneralUtility::makeInstance(ResourceFactory::class)

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\SubstituteResourceFactoryRector`](../src/Rector/v10/v3/SubstituteResourceFactoryRector.php)

```diff
-$resourceFactory = ResourceFactory::getInstance();
+$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
```

<br>

## SwiftMailerBasedMailMessageToMailerBasedMessageRector

New Mail API based on symfony/mailer and symfony/mime

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\SwiftMailerBasedMailMessageToMailerBasedMessageRector`](../src/Rector/v10/v0/SwiftMailerBasedMailMessageToMailerBasedMessageRector.php)

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

## SwitchBehaviorOfArrayUtilityMethodsRector

Handles the methods `arrayDiffAssocRecursive()` and `arrayDiffKeyRecursive()` of ArrayUtility

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\SwitchBehaviorOfArrayUtilityMethodsRector`](../src/Rector/v11/v3/SwitchBehaviorOfArrayUtilityMethodsRector.php)

```diff
 $foo = ArrayUtility::arrayDiffAssocRecursive([], [], true);
-$bar = ArrayUtility::arrayDiffAssocRecursive([], [], false);
-$test = ArrayUtility::arrayDiffAssocRecursive([], []);
+$bar = ArrayUtility::arrayDiffKeyRecursive([], []);
+$test = ArrayUtility::arrayDiffKeyRecursive([], []);
```

<br>

## SystemEnvironmentBuilderConstantsRector

GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector`](../src/Rector/v9/v4/SystemEnvironmentBuilderConstantsRector.php)

```diff
-$var1 = TYPO3_URL_MAILINGLISTS;
-$var2 = TYPO3_URL_DOCUMENTATION;
-$var3 = TYPO3_URL_DOCUMENTATION_TSREF;
-$var4 = TYPO3_URL_DOCUMENTATION_TSCONFIG;
-$var5 = TYPO3_URL_CONSULTANCY;
-$var6 = TYPO3_URL_CONTRIBUTE;
-$var7 = TYPO3_URL_SECURITY;
-$var8 = TYPO3_URL_DOWNLOAD;
-$var9 = TYPO3_URL_SYSTEMREQUIREMENTS;
-$nul = NUL;
-$tab = TAB;
-$sub = SUB;
+use TYPO3\CMS\Core\Service\AbstractService;
+$var1 = 'http://lists.typo3.org/cgi-bin/mailman/listinfo';
+$var2 = 'https://typo3.org/documentation/';
+$var3 = 'https://docs.typo3.org/typo3cms/TyposcriptReference/';
+$var4 = 'https://docs.typo3.org/typo3cms/TSconfigReference/';
+$var5 = 'https://typo3.org/support/professional-services/';
+$var6 = 'https://typo3.org/contribute/';
+$var7 = 'https://typo3.org/teams/security/';
+$var8 = 'https://typo3.org/download/';
+$var9 = 'https://typo3.org/typo3-cms/overview/requirements/';
+$nul = "\0";
+$tab = "\t";
+$sub = chr(26);

-$var10 = T3_ERR_SV_GENERAL;
-$var11 = T3_ERR_SV_NOT_AVAIL;
-$var12 = T3_ERR_SV_WRONG_SUBTYPE;
-$var13 = T3_ERR_SV_NO_INPUT;
-$var14 = T3_ERR_SV_FILE_NOT_FOUND;
-$var15 = T3_ERR_SV_FILE_READ;
-$var16 = T3_ERR_SV_FILE_WRITE;
-$var17 = T3_ERR_SV_PROG_NOT_FOUND;
-$var18 = T3_ERR_SV_PROG_FAILED;
+$var10 = AbstractService::ERROR_GENERAL;
+$var11 = AbstractService::ERROR_SERVICE_NOT_AVAILABLE;
+$var12 = AbstractService::ERROR_WRONG_SUBTYPE;
+$var13 = AbstractService::ERROR_NO_INPUT;
+$var14 = AbstractService::ERROR_FILE_NOT_FOUND;
+$var15 = AbstractService::ERROR_FILE_NOT_READABLE;
+$var16 = AbstractService::ERROR_FILE_NOT_WRITEABLE;
+$var17 = AbstractService::ERROR_PROGRAM_NOT_FOUND;
+$var18 = AbstractService::ERROR_PROGRAM_FAILED;
```

<br>

## TemplateGetFileNameToFilePathSanitizerRector

Use `FilePathSanitizer->sanitize()` instead of `TemplateService->getFileName()`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\TemplateGetFileNameToFilePathSanitizerRector`](../src/Rector/v9/v4/TemplateGetFileNameToFilePathSanitizerRector.php)

```diff
-$fileName = $GLOBALS['TSFE']->tmpl->getFileName('foo.text');
+$fileName = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize((string) 'foo.text');
```

<br>

## TemplateServiceSplitConfArrayRector

Substitute `TemplateService->splitConfArray()` with `TypoScriptService->explodeConfigurationForOptionSplit()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\TemplateServiceSplitConfArrayRector`](../src/Rector/v8/v7/TemplateServiceSplitConfArrayRector.php)

```diff
-$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
+$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
```

<br>

## TemplateToFluidTemplateTypoScriptRector

Convert TEMPLATE to FLUIDTEMPLATE

- class: [`Ssch\TYPO3Rector\FileProcessor\TypoScript\Rector\v11\v0\TemplateToFluidTemplateTypoScriptRector`](../src/FileProcessor/TypoScript/Rector/v11/v0/TemplateToFluidTemplateTypoScriptRector.php)

```diff
-page.10 = TEMPLATE
+page.10 = FLUIDTEMPLATE
```

<br>

## TimeTrackerGlobalsToSingletonRector

Substitute `$GLOBALS['TT']` method calls

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerGlobalsToSingletonRector`](../src/Rector/v8/v0/TimeTrackerGlobalsToSingletonRector.php)

```diff
-$GLOBALS['TT']->setTSlogMessage('content');
+GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
```

<br>

## TimeTrackerInsteadOfNullTimeTrackerRector

Use class TimeTracker instead of NullTimeTracker

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerInsteadOfNullTimeTrackerRector`](../src/Rector/v8/v0/TimeTrackerInsteadOfNullTimeTrackerRector.php)

```diff
-use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
+use TYPO3\CMS\Core\TimeTracker\TimeTracker;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$timeTracker1 = new NullTimeTracker();
-$timeTracker2 = GeneralUtility::makeInstance(NullTimeTracker::class);
+$timeTracker1 = new TimeTracker(false);
+$timeTracker2 = GeneralUtility::makeInstance(TimeTracker::class, false);
```

<br>

## TranslationFileRector

Use key translationFiles instead of translationFile

- class: [`Ssch\TYPO3Rector\FileProcessor\Yaml\Form\Rector\v10\v0\TranslationFileRector`](../src/FileProcessor/Yaml/Form/Rector/v10/v0/TranslationFileRector.php)

```diff
 TYPO3:
   CMS:
     Form:
       prototypes:
         standard:
           formElementsDefinition:
             Form:
               renderingOptions:
                 translation:
-                  translationFile:
-                    10: 'EXT:form/Resources/Private/Language/locallang.xlf'
+                  translationFiles:
                     20: 'EXT:myextension/Resources/Private/Language/locallang.xlf'
```

<br>

## TypeHandlingServiceToTypeHandlingUtilityRector

Use TypeHandlingUtility instead of TypeHandlingService

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\TypeHandlingServiceToTypeHandlingUtilityRector`](../src/Rector/v7/v0/TypeHandlingServiceToTypeHandlingUtilityRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-use TYPO3\CMS\Extbase\Service\TypeHandlingService;
-GeneralUtility::makeInstance(TypeHandlingService::class)->isSimpleType('string');
+use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
+TypeHandlingUtility::isSimpleType('string');
```

<br>

## TypoScriptFrontendControllerCharsetConverterRector

Refactor `$TSFE->csConvObj` and `$TSFE->csConv()`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\TypoScriptFrontendControllerCharsetConverterRector`](../src/Rector/v8/v1/TypoScriptFrontendControllerCharsetConverterRector.php)

```diff
-$output = $GLOBALS['TSFE']->csConvObj->conv_case('utf-8', 'foobar', 'lower');
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Charset\CharsetConverter;
+$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
+$output = $charsetConverter->conv_case('utf-8', 'foobar', 'lower');
```

<br>

## UnifiedFileNameValidatorRector

GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\UnifiedFileNameValidatorRector`](../src/Rector/v10/v4/UnifiedFileNameValidatorRector.php)

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

## UniqueListFromStringUtilityRector

Use `StringUtility::uniqueList()` instead of GeneralUtility::uniqueList

- class: [`Ssch\TYPO3Rector\Rector\v11\v0\UniqueListFromStringUtilityRector`](../src/Rector/v11/v0/UniqueListFromStringUtilityRector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::uniqueList('1,2,2,3');
+use TYPO3\CMS\Core\Utility\StringUtility;
+StringUtility::uniqueList('1,2,2,3');
```

<br>

## UseActionControllerRector

Use ActionController class instead of AbstractController if used

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\UseActionControllerRector`](../src/Rector/v10/v2/UseActionControllerRector.php)

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

## UseAddJsFileInsteadOfLoadJavascriptLibRector

Use method addJsFile of class PageRenderer instead of method loadJavascriptLib of class ModuleTemplate

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector`](../src/Rector/v9/v4/UseAddJsFileInsteadOfLoadJavascriptLibRector.php)

```diff
 use TYPO3\CMS\Backend\Template\ModuleTemplate;
+use TYPO3\CMS\Core\Page\PageRenderer;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 $moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
-$moduleTemplate->loadJavascriptLib('sysext/backend/Resources/Public/JavaScript/md5.js');
+GeneralUtility::makeInstance(PageRenderer::class)->addJsFile('sysext/backend/Resources/Public/JavaScript/md5.js');
```

<br>

## UseCachingFrameworkInsteadGetAndStoreHashRector

Use the Caching Framework directly instead of methods PageRepository::getHash and PageRepository::storeHash

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector`](../src/Rector/v8/v7/UseCachingFrameworkInsteadGetAndStoreHashRector.php)

```diff
-$GLOBALS['TSFE']->sys_page->storeHash('hash', ['foo', 'bar', 'baz'], 'ident');
-$hashContent2 = $GLOBALS['TSFE']->sys_page->getHash('hash');
+use TYPO3\CMS\Core\Cache\CacheManager;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set('hash', ['foo', 'bar', 'baz'], ['ident_' . 'ident'], 0);
+$hashContent = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->get('hash');
```

<br>

## UseClassSchemaInsteadReflectionServiceMethodsRector

Instead of fetching reflection data via ReflectionService use ClassSchema directly

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector`](../src/Rector/v9/v4/UseClassSchemaInsteadReflectionServiceMethodsRector.php)

```diff
 use TYPO3\CMS\Extbase\Reflection\ReflectionService;
 class MyService
 {
     /**
      * @var ReflectionService
      * @inject
      */
     protected $reflectionService;

     public function init(): void
     {
-        $properties = $this->reflectionService->getClassPropertyNames(\stdClass::class);
+        $properties = array_keys($this->reflectionService->getClassSchema(stdClass::class)->getProperties());
     }
 }
```

<br>

## UseClassTypo3InformationRector

Use class Typo3Information

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3InformationRector`](../src/Rector/v10/v3/UseClassTypo3InformationRector.php)

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

## UseClassTypo3VersionRector

Use class Typo3Version instead of the constants

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector`](../src/Rector/v10/v3/UseClassTypo3VersionRector.php)

```diff
-$typo3Version = TYPO3_version;
-$typo3Branch = TYPO3_branch;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Information\Typo3Version;
+$typo3Version = GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
+$typo3Branch = GeneralUtility::makeInstance(Typo3Version::class)->getBranch();
```

<br>

## UseCompositeExpressionStaticMethodsRector

Use CompositeExpression static methods instead of constructor

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector`](../src/Rector/v12/v0/typo3/UseCompositeExpressionStaticMethodsRector.php)

```diff
-$compositeExpressionAND = new CompositeExpression(CompositeExpression::TYPE_AND, []);
-$compositeExpressionOR = new CompositeExpression(CompositeExpression::TYPE_OR, []);
+$compositeExpressionAND = CompositeExpression::and([]);
+$compositeExpressionOR = CompositeExpression::or([]);
```

<br>

## UseConfigArrayForTSFEPropertiesRector

Use config array of TSFE instead of properties

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typo3\UseConfigArrayForTSFEPropertiesRector`](../src/Rector/v12/v0/typo3/UseConfigArrayForTSFEPropertiesRector.php)

```diff
-$fileTarget = $GLOBALS['TSFE']->fileTarget;
+$fileTarget = $GLOBALS['TSFE']->config['config']['fileTarget'];
```

<br>

## UseConfigArrayForTSFEPropertiesRector

Use config array of TSFE instead of properties

- class: [`Ssch\TYPO3Rector\Rector\v12\v0\typoscript\UseConfigArrayForTSFEPropertiesRector`](../src/Rector/v12/v0/typoscript/UseConfigArrayForTSFEPropertiesRector.php)

```diff
-.data = TSFE:fileTarget
+.data = TSFE:config|config|fileTarget
```

<br>

## UseContextApiForVersioningWorkspaceIdRector

Use context API instead of versioningWorkspaceId

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector`](../src/Rector/v9/v4/UseContextApiForVersioningWorkspaceIdRector.php)

```diff
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
 $workspaceId = null;
-$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;
+$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

 $GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
```

<br>

## UseContextApiRector

Various public properties in favor of Context API

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector`](../src/Rector/v9/v4/UseContextApiRector.php)

```diff
-$frontendUserIsLoggedIn = $GLOBALS['TSFE']->loginUser;
-$groupList = $GLOBALS['TSFE']->gr_list;
-$backendUserIsLoggedIn = $GLOBALS['TSFE']->beUserLogin;
-$showHiddenPage = $GLOBALS['TSFE']->showHiddenPage;
-$showHiddenRecords = $GLOBALS['TSFE']->showHiddenRecords;
+$frontendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'isLoggedIn');
+$groupList = implode(',', \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('frontend.user', 'groupIds'));
+$backendUserIsLoggedIn = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('backend.user', 'isLoggedIn');
+$showHiddenPage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenPages');
+$showHiddenRecords = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getPropertyFromAspect('visibility', 'includeHiddenContent');
```

<br>

## UseControllerClassesInExtbasePluginsAndModulesRector

Use controller classes when registering extbase plugins/modules

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector`](../src/Rector/v10/v0/UseControllerClassesInExtbasePluginsAndModulesRector.php)

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

## UseExtPrefixForTcaIconFileRector

Deprecate relative path to extension directory and using filename only in TCA ctrl iconfile

- class: [`Ssch\TYPO3Rector\Rector\v7\v5\UseExtPrefixForTcaIconFileRector`](../src/Rector/v7/v5/UseExtPrefixForTcaIconFileRector.php)

```diff
 [
     'ctrl' => [
-        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('my_extension') . 'Resources/Public/Icons/image.png'
+        'iconfile' => 'EXT:my_extension/Resources/Public/Icons/image.png'
     ]
 ];
```

<br>

## UseExtensionConfigurationApiRector

Use the new ExtensionConfiguration API instead of `$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo']`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector`](../src/Rector/v9/v0/UseExtensionConfigurationApiRector.php)

```diff
-$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);
+use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
```

<br>

## UseFileGetContentsForGetUrlRector

Rewirte Method Calls of GeneralUtility::getUrl("somefile.csv") to `@file_get_contents`

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\UseFileGetContentsForGetUrlRector`](../src/Rector/v10/v4/UseFileGetContentsForGetUrlRector.php)

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

## UseGetMenuInsteadOfGetFirstWebPageRector

Use method getMenu instead of getFirstWebPage

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseGetMenuInsteadOfGetFirstWebPageRector`](../src/Rector/v9/v4/UseGetMenuInsteadOfGetFirstWebPageRector.php)

```diff
-$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);
+$theFirstPage = reset($GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false));
```

<br>

## UseHtmlSpecialCharsDirectlyForTranslationRector

htmlspecialchars directly to properly escape the content.

- class: [`Ssch\TYPO3Rector\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector`](../src/Rector/v8/v2/UseHtmlSpecialCharsDirectlyForTranslationRector.php)

```diff
 use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;
 class MyPlugin extends AbstractPlugin
 {
     public function translate($hsc): void
     {
-        $translation = $this->pi_getLL('label', '', true);
-        $translation2 = $this->pi_getLL('label', '', false);
+        $translation = htmlspecialchars($this->pi_getLL('label', ''));
+        $translation2 = $this->pi_getLL('label', '');
         $translation3 = $this->pi_getLL('label', '', $hsc);
-        $translation9 = $GLOBALS['LANG']->sL('foobar', true);
-        $translation10 = $GLOBALS['LANG']->sL('foobar', false);
+        $translation9 = htmlspecialchars($GLOBALS['LANG']->sL('foobar'));
+        $translation10 = $GLOBALS['LANG']->sL('foobar');
     }
 }
```

<br>

## UseIconsFromSubFolderInIconRegistryRector

Use icons from subfolder in IconRegistry

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\UseIconsFromSubFolderInIconRegistryRector`](../src/Rector/v10/v4/UseIconsFromSubFolderInIconRegistryRector.php)

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

## UseLanguageAspectForTsfeLanguagePropertiesRector

Use LanguageAspect instead of language properties of TSFE

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector`](../src/Rector/v9/v4/UseLanguageAspectForTsfeLanguagePropertiesRector.php)

```diff
-$languageUid = $GLOBALS['TSFE']->sys_language_uid;
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+$languageUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
```

<br>

## UseMetaDataAspectRector

Use `$fileObject->getMetaData()->get()` instead of `$fileObject->_getMetaData()`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector`](../src/Rector/v10/v0/UseMetaDataAspectRector.php)

```diff
 $fileObject = new File();
-$fileObject->_getMetaData();
+$fileObject->getMetaData()->get();
```

<br>

## UseMethodGetPageShortcutDirectlyFromSysPageRector

Use method getPageShortcut directly from PageRepository

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\UseMethodGetPageShortcutDirectlyFromSysPageRector`](../src/Rector/v9/v3/UseMethodGetPageShortcutDirectlyFromSysPageRector.php)

```diff
-$GLOBALS['TSFE']->getPageShortcut('shortcut', 1, 1);
+$GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1);
```

<br>

## UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector

Use php native function instead of GeneralUtility::shortMd5

- class: [`Ssch\TYPO3Rector\Rector\v11\v4\UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector`](../src/Rector/v11/v4/UseNativeFunctionInsteadOfGeneralUtilityShortMd5Rector.php)

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
 $length = 10;
 $input = 'value';

-$shortMd5 = GeneralUtility::shortMD5($input, $length);
+$shortMd5 = substr(md5($input), 0, $length);
```

<br>

## UseNativePhpHex2binMethodRector

Turns TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php hex2bin

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector`](../src/Rector/v10/v0/UseNativePhpHex2binMethodRector.php)

```diff
-TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br>

## UseNewComponentIdForPageTreeRector

Use TYPO3/CMS/Backend/PageTree/PageTreeElement instead of typo3-pagetree

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector`](../src/Rector/v9/v0/UseNewComponentIdForPageTreeRector.php)

```diff
 TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
       'TYPO3.CMS.Workspaces',
       'web',
       'workspaces',
       'before:info',
       [
           // An array holding the controller-action-combinations that are accessible
           'Review' => 'index,fullIndex,singleIndex',
           'Preview' => 'index,newPage'
       ],
       [
           'access' => 'user,group',
           'icon' => 'EXT:workspaces/Resources/Public/Icons/module-workspaces.svg',
           'labels' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_mod.xlf',
-          'navigationComponentId' => 'typo3-pagetree'
+          'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement'
       ]
   );
```

<br>

## UseNormalizedParamsToGetRequestUrlRector

Use normalized params to get the request url

- class: [`Ssch\TYPO3Rector\Rector\v11\v3\typo3\UseNormalizedParamsToGetRequestUrlRector`](../src/Rector/v11/v3/typo3/UseNormalizedParamsToGetRequestUrlRector.php)

```diff
-$requestUri = $this->request->getRequestUri();
+$requestUri = $this->request->getAttribute('normalizedParams')->getRequestUrl();
```

<br>

## UsePackageManagerActivePackagesRector

Use PackageManager API instead of `$GLOBALS['TYPO3_LOADED_EXT']`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\UsePackageManagerActivePackagesRector`](../src/Rector/v9/v5/UsePackageManagerActivePackagesRector.php)

```diff
-$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
+$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
```

<br>

## UseRenderingContextGetControllerContextRector

Get controllerContext from renderingContext

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector`](../src/Rector/v9/v0/UseRenderingContextGetControllerContextRector.php)

```diff
 class MyViewHelperAccessingControllerContext extends AbstractViewHelper
 {
     public function render()
     {
-        $controllerContext = $this->controllerContext;
+        $controllerContext = $this->renderingContext->getControllerContext();
     }
 }
```

<br>

## UseRootlineUtilityInsteadOfGetRootlineMethodRector

Use class RootlineUtility instead of method getRootLine

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethodRector`](../src/Rector/v9/v4/UseRootlineUtilityInsteadOfGetRootlineMethodRector.php)

```diff
-$rootline = $GLOBALS['TSFE']->sys_page->getRootLine(1);
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\RootlineUtility;
+$rootline = GeneralUtility::makeInstance(RootlineUtility::class, 1)->get();
```

<br>

## UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector

Use the signal afterExtensionInstall of class InstallUtility

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector`](../src/Rector/v9/v4/UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
-use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;
+use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;

 $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
 $signalSlotDispatcher->connect(
-    ExtensionManagementService::class,
-    'hasInstalledExtensions',
+    InstallUtility::class,
+    'afterExtensionInstall',
     \stdClass::class,
     'foo'
 );
```

<br>

## UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector

Use the signal tablesDefinitionIsBeingBuilt of class SqlExpectedSchemaService

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector`](../src/Rector/v9/v4/UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector.php)

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
-use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
+use TYPO3\CMS\Install\Service\SqlExpectedSchemaService;

 $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
 $signalSlotDispatcher->connect(
-    InstallUtility::class,
+    SqlExpectedSchemaService::class,
     'tablesDefinitionIsBeingBuilt',
     \stdClass::class,
     'foo'
 );
```

<br>

## UseTwoLetterIsoCodeFromSiteLanguageRector

The usage of the propery sys_language_isocode is deprecated. Use method getTwoLetterIsoCode of SiteLanguage

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector`](../src/Rector/v10/v0/UseTwoLetterIsoCodeFromSiteLanguageRector.php)

```diff
-if ($GLOBALS['TSFE']->sys_language_isocode) {
-    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
+if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
+    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
 }
```

<br>

## UseTypo3InformationForCopyRightNoticeRector

Migrate the method `BackendUtility::TYPO3_copyRightNotice()` to use Typo3Information API

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\UseTypo3InformationForCopyRightNoticeRector`](../src/Rector/v10/v2/UseTypo3InformationForCopyRightNoticeRector.php)

```diff
-$copyright = BackendUtility::TYPO3_copyRightNotice();
+$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
```

<br>

## ValidateAnnotationRector

Turns properties with `@validate` to properties with `@TYPO3\CMS\Extbase\Annotation\Validate`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\ValidateAnnotationRector`](../src/Rector/v9/v3/ValidateAnnotationRector.php)

```diff
+use TYPO3\CMS\Extbase\Annotation as Extbase;
 /**
- * @validate NotEmpty
- * @validate StringLength(minimum=0, maximum=255)
+ * @Extbase\Validate("NotEmpty")
+ * @Extbase\Validate("StringLength", options={"minimum": 3, "maximum": 50})
  */
 private $someProperty;
```

<br>

## WrapClickMenuOnIconRector

Use method wrapClickMenuOnIcon of class BackendUtility

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\WrapClickMenuOnIconRector`](../src/Rector/v7/v6/WrapClickMenuOnIconRector.php)

```diff
-DocumentTemplate->wrapClickMenuOnIcon
+BackendUtility::wrapClickMenuOnIcon()
```

<br>
