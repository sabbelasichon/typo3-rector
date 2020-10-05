# All 65 Rectors Overview

## `AddCodeCoverageIgnoreToMethodRectorDefinitionRector`

- class: [`Ssch\TYPO3Rector\Rector\Misc\AddCodeCoverageIgnoreToMethodRectorDefinitionRector`](/src/Rector/Misc/AddCodeCoverageIgnoreToMethodRectorDefinitionRector.php)
- [test fixtures](/tests/Rector/Misc/Fixture)

Adds @codeCoverageIgnore annotation to to method getDefinition

```diff
 class SomeClass extends AbstractRector
 {
     public function getNodeTypes(): array
     {
     }

     public function refactor(Node $node): ?Node
     {
     }

+    /**
+     * @codeCoverageIgnore
+     */
     public function getDefinition(): RectorDefinition
     {
     }
 }
```

<br><br>

## `BackendUtilityEditOnClickRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityEditOnClickRector`](/src/Rector/Backend/Utility/BackendUtilityEditOnClickRector.php)
- [test fixtures](/tests/Rector/Backend/Utility/Fixture)

Migrate the method `BackendUtility::editOnClick()` to use UriBuilder API

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br><br>

## `BackendUtilityGetModuleUrlRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityGetModuleUrlRector`](/src/Rector/Backend/Utility/BackendUtilityGetModuleUrlRector.php)
- [test fixtures](/tests/Rector/Backend/Utility/Fixture)

Migrate the method `BackendUtility::getModuleUrl()` to use UriBuilder API

```diff
 $moduleName = 'record_edit';
 $params = ['pid' => 2];
-$url = BackendUtility::getModuleUrl($moduleName, $params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);
```

<br><br>

## `BackendUtilityGetRecordRawRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityGetRecordRawRector`](/src/Rector/Backend/Utility/BackendUtilityGetRecordRawRector.php)
- [test fixtures](/tests/Rector/Backend/Utility/Fixture)

Migrate the method `BackendUtility::editOnClick()` to use UriBuilder API

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

<br><br>

## `CallEnableFieldsFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\CallEnableFieldsFromPageRepositoryRector`](/src/Rector/Frontend/ContentObject/CallEnableFieldsFromPageRepositoryRector.php)
- [test fixtures](/tests/Rector/Frontend/Page/Fixture)

Call enable fields from PageRepository instead of ContentObjectRenderer

```diff
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->enableFields('pages', false, []);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
```

<br><br>

## `ChangeAttemptsParameterConsoleOutputRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector`](/src/Rector/Extbase/ChangeAttemptsParameterConsoleOutputRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Turns old default value to parameter in `ConsoleOutput->askAndValidate()` and/or `ConsoleOutput->select()` method

```diff
-$this->output->select('The question', [1, 2, 3], null, false, false);
+$this->output->select('The question', [1, 2, 3], null, false, null);
```

<br><br>

## `ChangeMethodCallsForStandaloneViewRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector`](/src/Rector/Fluid/View/ChangeMethodCallsForStandaloneViewRector.php)
- [test fixtures](/tests/Rector/Fluid/View/Fixture)

Turns method call names to new ones.

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

<br><br>

## `CheckForExtensionInfoRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\CheckForExtensionInfoRector`](/src/Rector/Core/CheckForExtensionInfoRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Change the extensions to check for info instead of info_pagetsconfig.

```diff
-if(ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {

+if(ExtensionManagementUtility::isLoaded('info')) {
+
 }

 $packageManager = GeneralUtility::makeInstance(PackageManager::class);
-if($packageManager->isActive('info_pagetsconfig')) {
+if($packageManager->isActive('info')) {

 }
```

<br><br>

## `CheckForExtensionVersionRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\CheckForExtensionVersionRector`](/src/Rector/Core/CheckForExtensionVersionRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Change the extensions to check for workspaces instead of version.

```diff
-if (ExtensionManagementUtility::isLoaded('version')) {
+if (ExtensionManagementUtility::isLoaded('workspaces')) {
 }

 $packageManager = GeneralUtility::makeInstance(PackageManager::class);
-if ($packageManager->isActive('version')) {
+if ($packageManager->isActive('workspaces')) {
 }
```

<br><br>

## `ConfigurationManagerAddControllerConfigurationMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\ConfigurationManagerAddControllerConfigurationMethodRector`](/src/Rector/Extbase/ConfigurationManagerAddControllerConfigurationMethodRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Add additional method getControllerConfiguration for AbstractConfigurationManager

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

<br><br>

## `ConstantToEnvironmentCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector`](/src/Rector/Core/Environment/ConstantToEnvironmentCallRector.php)

Turns defined constant to static method call of new Environment API.

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
```

<br><br>

## `DataHandlerRmCommaRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\DataHandling\DataHandlerRmCommaRector`](/src/Rector/Core/DataHandling/DataHandlerRmCommaRector.php)
- [test fixtures](/tests/Rector/Core/DataHandling/Fixture)

Migrate the method `DataHandler::rmComma()` to use `rtrim()`

```diff
 $inList = '1,2,3,';
 $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
-$inList = $dataHandler->rmComma(trim($inList));
+$inList = rtrim(trim($inList), ',');
```

<br><br>

## `DatabaseConnectionToDbalRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Database\DatabaseConnectionToDbalRector`](/src/Rector/Core/Database/DatabaseConnectionToDbalRector.php)

Refactor legacy calls of DatabaseConnection to Dbal

```diff
-$GLOBALS['TYPO3_DB']->exec_INSERTquery(
+$connectionPool = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class);
+        $databaseConnectionForPages = $connectionPool->getConnectionForTable('pages');
+        $databaseConnectionForPages->insert(
             'pages',
             [
                 'pid' => 0,
                 'title' => 'Home',
             ]
         );
```

<br><br>

## `ExcludeServiceKeysToArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\ExcludeServiceKeysToArrayRector`](/src/Rector/Core/ExcludeServiceKeysToArrayRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Change parameter `$excludeServiceKeys` explicity to an array

```diff
-GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
-ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
+GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
+ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
```

<br><br>

## `FindByPidsAndAuthorIdRector`

- class: [`Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository\FindByPidsAndAuthorIdRector`](/src/Rector/SysNote/Domain/Repository/FindByPidsAndAuthorIdRector.php)
- [test fixtures](/tests/Rector/SysNote/Domain/Repository/Fixture)

Use findByPidsAndAuthorId instead of findByPidsAndAuthor

```diff
 $sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
 $backendUser = new BackendUser();
-$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
+$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
```

<br><br>

## `GeneratePageTitleRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector`](/src/Rector/v9/v0/GeneratePageTitleRector.php)
- [test fixtures](/tests/Rector/v9/v0/GeneratePageTitle/Fixture)

Use generatePageTitle of TSFE instead of class PageGenerator

```diff
 use TYPO3\CMS\Frontend\Page\PageGenerator;

-PageGenerator::generatePageTitle();
+$GLOBALS['TSFE']->generatePageTitle();
```

<br><br>

## `IgnoreValidationAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\IgnoreValidationAnnotationRector`](/src/Rector/Annotation/IgnoreValidationAnnotationRector.php)

Turns properties with `@ignorevalidation` to properties with `@TYPO3\CMS\Extbase\Annotation\IgnoreValidation`

```diff
 /**
- * @ignorevalidation $param
+ * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
  */
 public function method($param)
 {
 }
```

<br><br>

## `InjectAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\InjectAnnotationRector`](/src/Rector/Annotation/InjectAnnotationRector.php)

Turns properties with `@inject` to setter injection

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

<br><br>

## `InjectEnvironmentServiceIfNeededInResponseRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\InjectEnvironmentServiceIfNeededInResponseRector`](/src/Rector/Extbase/InjectEnvironmentServiceIfNeededInResponseRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Inject EnvironmentService if needed in subclass of Response

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

<br><br>

## `MetaTagManagementRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector`](/src/Rector/v9/v0/MetaTagManagementRector.php)
- [test fixtures](/tests/Rector/v9/v0/MetaTagManagement/Fixture)

Use setMetaTag method from PageRenderer class

```diff
 use TYPO3\CMS\Core\Page\PageRenderer;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$pageRenderer->addMetaTag('<meta name="keywords" content="seo, search engine optimisation, search engine optimization, search engine ranking">');
+$pageRenderer->setMetaTag('name', 'keywords', 'seo, search engine optimisation, search engine optimization, search engine ranking');
```

<br><br>

## `MoveApplicationContextToEnvironmentApiRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\MoveApplicationContextToEnvironmentApiRector`](/src/Rector/Core/Utility/MoveApplicationContextToEnvironmentApiRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Use Environment API to fetch application context

```diff
-GeneralUtility::getApplicationContext();
+Environment::getContext();
```

<br><br>

## `MoveRenderArgumentsToInitializeArgumentsMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector`](/src/Rector/Fluid/ViewHelpers/MoveRenderArgumentsToInitializeArgumentsMethodRector.php)
- [test fixtures](/tests/Rector/Fluid/ViewHelpers/Fixture)

Move render method arguments to initializeArguments method

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

<br><br>

## `RefactorDbConstantsRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\RefactorDbConstantsRector`](/src/Rector/Core/RefactorDbConstantsRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Changes TYPO3_db constants to `$GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'].`

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

<br><br>

## `RefactorDeprecatedConcatenateMethodsPageRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Page\RefactorDeprecatedConcatenateMethodsPageRendererRector`](/src/Rector/Core/Page/RefactorDeprecatedConcatenateMethodsPageRendererRector.php)
- [test fixtures](/tests/Rector/Core/Page/Fixture)

Turns method call names to new ones.

```diff
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$files = $someObject->getConcatenateFiles();
+$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
```

<br><br>

## `RefactorDeprecationLogRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorDeprecationLogRector`](/src/Rector/Core/Utility/RefactorDeprecationLogRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Refactor GeneralUtility deprecationLog methods

```diff
-GeneralUtility::logDeprecatedFunction();
-GeneralUtility::logDeprecatedViewHelperAttribute();
-GeneralUtility::deprecationLog('Message');
-GeneralUtility::getDeprecationLogFileName();
+trigger_error('A useful message', E_USER_DEPRECATED);
```

<br><br>

## `RefactorExplodeUrl2ArrayFromGeneralUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorExplodeUrl2ArrayFromGeneralUtilityRector`](/src/Rector/Core/Utility/RefactorExplodeUrl2ArrayFromGeneralUtilityRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function `parse_str` if it is true

```diff
-$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
-$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
+parse_str('https://www.domain.com', $variable);
+$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
```

<br><br>

## `RefactorIdnaEncodeMethodToNativeFunctionRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorIdnaEncodeMethodToNativeFunctionRector`](/src/Rector/Core/Utility/RefactorIdnaEncodeMethodToNativeFunctionRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Use native function `idn_to_ascii` instead of GeneralUtility::idnaEncode

```diff
-$domain = GeneralUtility::idnaEncode('domain.com');
-$email = GeneralUtility::idnaEncode('email@domain.com');
+$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
+$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
```

<br><br>

## `RefactorMethodsFromExtensionManagementUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorMethodsFromExtensionManagementUtilityRector`](/src/Rector/Core/Utility/RefactorMethodsFromExtensionManagementUtilityRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Refactor deprecated methods from ExtensionManagementUtility.

```diff
-ExtensionManagementUtility::removeCacheFiles();
+GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
```

<br><br>

## `RefactorRemovedMarkerMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMarkerMethodsFromContentObjectRendererRector`](/src/Rector/Frontend/ContentObject/RefactorRemovedMarkerMethodsFromContentObjectRendererRector.php)
- [test fixtures](/tests/Rector/Frontend/ContentObject/Fixture)

Refactor removed Marker-related methods from ContentObjectRenderer.

```diff
 // build template
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

<br><br>

## `RefactorRemovedMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector`](/src/Rector/Frontend/ContentObject/RefactorRemovedMethodsFromContentObjectRendererRector.php)
- [test fixtures](/tests/Rector/Frontend/ContentObject/Fixture)

Refactor removed methods from ContentObjectRenderer.

```diff
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
```

<br><br>

## `RefactorRemovedMethodsFromGeneralUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector`](/src/Rector/Core/Utility/RefactorRemovedMethodsFromGeneralUtilityRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Refactor removed methods from GeneralUtility.

```diff
-GeneralUtility::gif_compress();
+\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
```

<br><br>

## `RegisterPluginWithVendorNameRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RegisterPluginWithVendorNameRector`](/src/Rector/Extbase/RegisterPluginWithVendorNameRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Remove vendor name from registerPlugin call

```diff
 \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
-   'TYPO3.CMS.Form',
+   'Form',
    'Formframework',
    'Form',
    'content-form',
 );
```

<br><br>

## `RemoveColPosParameterRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization\RemoveColPosParameterRector`](/src/Rector/Backend/Domain/Repository/Localization/RemoveColPosParameterRector.php)

Remove parameter colPos from methods.

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
```

<br><br>

## `RemoveFlushCachesRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemoveFlushCachesRector`](/src/Rector/Extbase/RemoveFlushCachesRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Remove @flushesCaches annotation

```diff
 /**
- * My command
- *
- * @flushesCaches
+ * My Command
  */
 public function myCommand()
 {
-}
+}
```

<br><br>

## `RemoveInitMethodFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\Page\RemoveInitMethodFromPageRepositoryRector`](/src/Rector/Frontend/Page/RemoveInitMethodFromPageRepositoryRector.php)
- [test fixtures](/tests/Rector/Frontend/Page/Fixture)

Remove method call init from PageRepository

```diff
-$repository = GeneralUtility::makeInstance(PageRepository::class);
-$repository->init(true);
+$repository = GeneralUtility::makeInstance(PageRepository::class);
```

<br><br>

## `RemoveInitTemplateMethodCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\Controller\RemoveInitTemplateMethodCallRector`](/src/Rector/Frontend/Controller/RemoveInitTemplateMethodCallRector.php)
- [test fixtures](/tests/Rector/Frontend/Controller/Fixture)

Remove method call initTemplate from TypoScriptFrontendController

```diff
-$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->initTemplate();
+$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
```

<br><br>

## `RemoveInternalAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemoveInternalAnnotationRector`](/src/Rector/Extbase/RemoveInternalAnnotationRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Remove @internal annotation from classes extending \TYPO3\CMS\Extbase\Mvc\Controller\CommandController

```diff
-/**
- * @internal
- */
 class MyCommandController extends CommandController
 {
 }
```

<br><br>

## `RemoveMethodInitTCARector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector`](/src/Rector/v9/v0/RemoveMethodInitTCARector.php)
- [test fixtures](/tests/Rector/v9/v0/RemoveMethodInitTCA/Fixture)

Remove superfluous EidUtility::initTCA call

```diff
-use TYPO3\CMS\Frontend\Utility\EidUtility;
-EidUtility::initTCA();
```

<br><br>

## `RemovePropertiesFromSimpleDataHandlerControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector`](/src/Rector/Backend/Controller/RemovePropertiesFromSimpleDataHandlerControllerRector.php)
- [test fixtures](/tests/Rector/Backend/Controller/Fixture)

Remove assignments or accessing of properties prErr and uPT from class SimpleDataHandlerController

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

<br><br>

## `RemovePropertyExtensionNameRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyExtensionNameRector`](/src/Rector/Extbase/RemovePropertyExtensionNameRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Use method getControllerExtensionName from `$request` property instead of removed property `$extensionName`

```diff
 class MyCommandController extends CommandController
 {
     public function myMethod()
     {
-        if($this->extensionName === 'whatever') {
+        if($this->request->getControllerExtensionName() === 'whatever') {

         }

-        $extensionName = $this->extensionName;
+        $extensionName = $this->request->getControllerExtensionName();
     }
 }
```

<br><br>

## `RemovePropertyUserAuthenticationRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyUserAuthenticationRector`](/src/Rector/Extbase/RemovePropertyUserAuthenticationRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Use method getBackendUserAuthentication instead of removed property `$userAuthentication`

```diff
 class MyCommandController extends CommandController
 {
     public function myMethod()
     {
-        if($this->userAuthentication !== null) {
+        if($this->getBackendUserAuthentication() !== null) {

         }
     }
 }
```

<br><br>

## `RemoveSecondArgumentGeneralUtilityMkdirDeepRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector`](/src/Rector/Core/Utility/RemoveSecondArgumentGeneralUtilityMkdirDeepRector.php)
- [test fixtures](/tests/Rector/Core/Utility/Fixture)

Remove second argument of `GeneralUtility::mkdir_deep()`

```diff
-GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
```

<br><br>

## `RenameClassMapAliasRector`

- class: [`Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector`](/src/Rector/Migrations/RenameClassMapAliasRector.php)
- [test fixtures](/tests/Rector/Migrations/Fixture)

Replaces defined classes by new ones.

```php
<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(RenameClassMapAliasRector::class)
        ->call('configure', [[RenameClassMapAliasRector::CLASS_ALIAS_MAPS => 'config/Migrations/Code/ClassAliasMap.php']]);
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

<br><br>

## `RenameMethodCallToEnvironmentMethodCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector`](/src/Rector/Core/Environment/RenameMethodCallToEnvironmentMethodCallRector.php)

Turns method call names to new ones from new Environment API.

```diff
-Bootstrap::usesComposerClassLoading();
-GeneralUtility::getApplicationContext();
-EnvironmentService::isEnvironmentInCliMode();
+Environment::getContext();
+Environment::isComposerMode();
+Environment::isCli();
```

<br><br>

## `RenamePiListBrowserResultsRector`

- class: [`Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector`](/src/Rector/IndexedSearch/Controller/RenamePiListBrowserResultsRector.php)
- [test fixtures](/tests/Rector/IndexedSearch/Controller/Fixture)

Rename pi_list_browseresults calls to renderPagination

```diff
-$this->pi_list_browseresults
+$this->renderPagination
```

<br><br>

## `ReplaceAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\ReplaceAnnotationRector`](/src/Rector/Annotation/ReplaceAnnotationRector.php)

Replace old annotation by new one

```php
<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\Annotation\ReplaceAnnotationRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ReplaceAnnotationRector::class)
        ->call('configure', [[ReplaceAnnotationRector::OLD_TO_NEW_ANNOTATIONS => ['transient' => 'TYPO3\CMS\Extbase\Annotation\ORM\Transient']]]);
};
```

↓

```diff
 /**
- * @transient
+ * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
  */
-private $someProperty;
+private $someProperty;
```

<br><br>

## `RteHtmlParserRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Html\RteHtmlParserRector`](/src/Rector/Core/Html/RteHtmlParserRector.php)

Remove second argument of HTMLcleaner_db getKeepTags. Substitute calls for siteUrl getUrl

```diff
             use TYPO3\CMS\Core\Html\RteHtmlParser;
-
             $rteHtmlParser = new RteHtmlParser();
-            $rteHtmlParser->HTMLcleaner_db('arg1', 'arg2');
-            $rteHtmlParser->getKeepTags('arg1', 'arg2');
-            $rteHtmlParser->getUrl('http://domain.com');
-            $rteHtmlParser->siteUrl();
+            $rteHtmlParser->HTMLcleaner_db('arg1');
+            $rteHtmlParser->getKeepTags('arg1');
+            \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl('http://domain.com');
+             \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_SITE_URL');
```

<br><br>

## `SubstituteCacheWrapperMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector`](/src/Rector/v9/v0/SubstituteCacheWrapperMethodsRector.php)
- [test fixtures](/tests/Rector/v9/v0/SubstituteCacheMethods/Fixture)

Caching framework wrapper methods in BackendUtility

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
+use TYPO3\CMS\Core\Cache\CacheManager;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+
 $hash = 'foo';
-$content = BackendUtility::getHash($hash);
+$cacheManager = GeneralUtility::makeInstance(CacheManager::class);
+$cacheEntry = $cacheManager->getCache('cache_hash')->get($hash);
+$hashContent = null;
+if ($cacheEntry) {
+    $hashContent = $cacheEntry;
+}
+$content = $hashContent;
```

<br><br>

## `SubstituteConstantParsetimeStartRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\SubstituteConstantParsetimeStartRector`](/src/Rector/Core/SubstituteConstantParsetimeStartRector.php)
- [test fixtures](/tests/Rector/Core/Fixture)

Substitute `$GLOBALS['PARSETIME_START']` with round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000)

```diff
-$parseTime = $GLOBALS['PARSETIME_START'];
+$parseTime = round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000);
```

<br><br>

## `SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector`](/src/Rector/v10/v4/SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector.php)
- [test fixtures](/tests/Rector/v10/v4/SubstituteGeneralUtilityMethodsWithNativePhpFunctionsRector/Fixture)

Substitute deprecated method calls of class GeneralUtility

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

<br><br>

## `SubstituteResourceFactoryRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Resource\SubstituteResourceFactoryRector`](/src/Rector/Core/Resource/SubstituteResourceFactoryRector.php)
- [test fixtures](/tests/Rector/Core/Resource/Fixture)

Substitue `ResourceFactory::getInstance()` through GeneralUtility::makeInstance(ResourceFactory::class)

```diff
-$resourceFactory = ResourceFactory::getInstance();
+$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
```

<br><br>

## `SystemEnvironmentBuilderConstantsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector`](/src/Rector/v9/v4/SystemEnvironmentBuilderConstantsRector.php)
- [test fixtures](/tests/Rector/v9/v4/Fixture)

GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)

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

<br><br>

## `TemplateServiceSplitConfArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\TypoScript\TemplateServiceSplitConfArrayRector`](/src/Rector/Core/TypoScript/TemplateServiceSplitConfArrayRector.php)
- [test fixtures](/tests/Rector/Core/TypoScript/Fixture)

Substitute `TemplateService->splitConfArray()` with `TypoScriptService->explodeConfigurationForOptionSplit()`

```diff
-$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
+$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
```

<br><br>

## `TimeTrackerGlobalsToSingletonRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\TimeTracker\TimeTrackerGlobalsToSingletonRector`](/src/Rector/Core/TimeTracker/TimeTrackerGlobalsToSingletonRector.php)
- [test fixtures](/tests/Rector/Core/TimeTracker/Fixture)

Substitute `$GLOBALS['TT']` method calls

```diff
-$GLOBALS['TT']->setTSlogMessage('content');
+GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
```

<br><br>

## `UnifiedFileNameValidatorRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\UnifiedFileNameValidatorRector`](/src/Rector/v10/v4/UnifiedFileNameValidatorRector.php)
- [test fixtures](/tests/Rector/v10/v4/UnifiedFileNameValidatorRector/Fixture)

GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)

```diff
+use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $filename = 'somefile.php';
-if(!GeneralUtility::verifyFilenameAgainstDenyPattern($filename)) {
+if(!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)) {
 }

-if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FILE_DENY_PATTERN_DEFAULT)
+if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FileNameValidator::DEFAULT_FILE_DENY_PATTERN)
 {
 }
```

<br><br>

## `UseActionControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\UseActionControllerRector`](/src/Rector/Extbase/UseActionControllerRector.php)
- [test fixtures](/tests/Rector/Extbase/Fixture)

Use ActionController class instead of AbstractController if used

```diff
-class MyController extends AbstractController
+use Symfony\Component\HttpFoundation\Response;
+use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
+
+class MyController extends ActionController
 {
 }
```

<br><br>

## `UseClassTypo3VersionRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector`](/src/Rector/v10/v3/UseClassTypo3VersionRector.php)
- [test fixtures](/tests/Rector/v10/v3/Fixture)

Use class Typo3Version instead of the constants

```diff
-$typo3Version = TYPO3_version;
-$typo3Branch = TYPO3_branch;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Information\Typo3Version;
+$typo3Version = GeneralUtility::makeInstance(Typo3Version::class)->getVersion();
+$typo3Branch = GeneralUtility::makeInstance(Typo3Version::class)->getBranch();
```

<br><br>

## `UseContextApiRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector`](/src/Rector/v9/v4/UseContextApiRector.php)
- [test fixtures](/tests/Rector/v9/v4/UseContextApi/Fixture)

Various public properties in favor of Context API

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

<br><br>

## `UseLogMethodInsteadOfNewLog2Rector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector`](/src/Rector/v9/v0/UseLogMethodInsteadOfNewLog2Rector.php)
- [test fixtures](/tests/Rector/v9/v0/UseLogMethod/Fixture)

Use `log` method instead of newlog2 from class DataHandler

```diff
 use TYPO3\CMS\Core\DataHandling\DataHandler;
 use TYPO3\CMS\Core\Utility\GeneralUtility;

 $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
-$logEntryUid1 = $dataHandler->newlog2('Foo', 'pages', 1, null, 0);
-$logEntryUid2 = $dataHandler->newlog2('Foo', 'tt_content', 1, 2, 1);
-$logEntryUid3 = $dataHandler->newlog2('Foo', 'tt_content', 1);
+$propArr = $dataHandler->getRecordProperties('pages', 1);
+$pid = $propArr['pid'];
+
+$logEntryUid1 = $dataHandler->log('pages', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('pages', 1, $pid));
+$logEntryUid2 = $dataHandler->log('tt_content', 1, 0, 0, 1, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, 2));
+$propArr = $dataHandler->getRecordProperties('tt_content', 1);
+$pid = $propArr['pid'];
+
+$logEntryUid3 = $dataHandler->log('tt_content', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, $pid));
```

<br><br>

## `UseMetaDataAspectRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Resource\UseMetaDataAspectRector`](/src/Rector/Core/Resource/UseMetaDataAspectRector.php)
- [test fixtures](/tests/Rector/Core/Resource/Fixture)

Use `$fileObject->getMetaData()->get()` instead of `$fileObject->_getMetaData()`

```diff
 $fileObject = new File();
-$fileObject->_getMetaData();
+$fileObject->getMetaData()->get();
```

<br><br>

## `UseNativePhpHex2binMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\Utility\UseNativePhpHex2binMethodRector`](/src/Rector/Extbase/Utility/UseNativePhpHex2binMethodRector.php)
- [test fixtures](/tests/Rector/Extbase/Utility/Fixture)

Turns \TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php `hex2bin`

```diff
-\TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br><br>

## `UsePackageManagerActivePackagesRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Package\UsePackageManagerActivePackagesRector`](/src/Rector/Core/Package/UsePackageManagerActivePackagesRector.php)
- [test fixtures](/tests/Rector/Core/Package/Fixture)

Use PackageManager API instead of `$GLOBALS['TYPO3_LOADED_EXT']`

```diff
-$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
+$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
```

<br><br>

## `UseRenderingContextGetControllerContextRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\UseRenderingContextGetControllerContextRector`](/src/Rector/Fluid/ViewHelpers/UseRenderingContextGetControllerContextRector.php)
- [test fixtures](/tests/Rector/Fluid/ViewHelpers/Fixture)

Get controllerContext from renderingContext

```diff
 class MyViewHelperAccessingControllerContext extends AbstractViewHelper
 {
-    protected $controllerContext;
-
     public function render()
     {
-        $controllerContext = $this->controllerContext;
+        $controllerContext = $this->renderingContext->getControllerContext();
     }
 }
```

<br><br>

## `UseTypo3InformationForCopyRightNoticeRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\UseTypo3InformationForCopyRightNoticeRector`](/src/Rector/Backend/Utility/UseTypo3InformationForCopyRightNoticeRector.php)
- [test fixtures](/tests/Rector/Backend/Utility/Fixture)

Migrate the method `BackendUtility::TYPO3_copyRightNotice()` to use Typo3Information API

```diff
-$copyright = BackendUtility::TYPO3_copyRightNotice();
+$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
```

<br><br>

## `ValidateAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\ValidateAnnotationRector`](/src/Rector/Annotation/ValidateAnnotationRector.php)

Turns properties with `@validate` to properties with `@TYPO3\CMS\Extbase\Annotation\Validate`

```diff
 /**
- * @validate NotEmpty
- * @validate StringLength(minimum=0, maximum=255)
+ * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
+ * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 3, "maximum": 50})
  */
 private $someProperty;
```

<br><br>

