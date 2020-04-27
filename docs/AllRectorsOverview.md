# All 52 Rectors Overview

## `BackendUtilityEditOnClickRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityEditOnClickRector`](/../master/src/Rector/Backend/Utility/BackendUtilityEditOnClickRector.php)
- [test fixtures](/../master/tests/Rector/Backend/Utility/Fixture)

Migrate the method BackendUtility::editOnClick() to use UriBuilder API

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br>

## `BackendUtilityGetRecordRawRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityGetRecordRawRector`](/../master/src/Rector/Backend/Utility/BackendUtilityGetRecordRawRector.php)
- [test fixtures](/../master/tests/Rector/Backend/Utility/Fixture)

Migrate the method BackendUtility::editOnClick() to use UriBuilder API

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

## `CallEnableFieldsFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\CallEnableFieldsFromPageRepositoryRector`](/../master/src/Rector/Frontend/ContentObject/CallEnableFieldsFromPageRepositoryRector.php)
- [test fixtures](/../master/tests/Rector/Frontend/Page/Fixture)

Call enable fields from PageRepository instead of ContentObjectRenderer

```diff
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->enableFields('pages', false, []);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
```

<br>

## `CascadeAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\CascadeAnnotationRector`](/../master/src/Rector/Annotation/CascadeAnnotationRector.php)

Turns properties with `@cascade` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Cascade`

```diff
 /**
- * @cascade
+ * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
  */
-private $someProperty;
+private $someProperty;
```

<br>

## `ChangeAttemptsParameterConsoleOutputRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector`](/../master/src/Rector/Extbase/ChangeAttemptsParameterConsoleOutputRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

Turns old default value to parameter in ConsoleOutput->askAndValidate() and/or ConsoleOutput->select() method

```diff
-$this->output->select('The question', [1, 2, 3], null, false, false);
+$this->output->select('The question', [1, 2, 3], null, false, null);
```

<br>

## `ChangeMethodCallsForStandaloneViewRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector`](/../master/src/Rector/Fluid/View/ChangeMethodCallsForStandaloneViewRector.php)
- [test fixtures](/../master/tests/Rector/Fluid/View/Fixture)

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

<br>

## `CheckForExtensionInfoRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\CheckForExtensionInfoRector`](/../master/src/Rector/Core/CheckForExtensionInfoRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

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

<br>

## `CheckForExtensionVersionRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\CheckForExtensionVersionRector`](/../master/src/Rector/Core/CheckForExtensionVersionRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

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

<br>

## `ConfigurationManagerAddControllerConfigurationMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\ConfigurationManagerAddControllerConfigurationMethodRector`](/../master/src/Rector/Extbase/ConfigurationManagerAddControllerConfigurationMethodRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

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

<br>

## `ConstantToEnvironmentCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector`](/../master/src/Rector/Core/Environment/ConstantToEnvironmentCallRector.php)

Turns defined constant to static method call of new Environment API.

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
```

<br>

## `DataHandlerRmCommaRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\DataHandling\DataHandlerRmCommaRector`](/../master/src/Rector/Core/DataHandling/DataHandlerRmCommaRector.php)
- [test fixtures](/../master/tests/Rector/Core/DataHandling/Fixture)

Migrate the method DataHandler::rmComma() to use rtrim()

```diff
 $inList = '1,2,3,';
 $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
-$inList = $dataHandler->rmComma(trim($inList));
+$inList = rtrim(trim($inList), ',');
```

<br>

## `ExcludeServiceKeysToArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\ExcludeServiceKeysToArrayRector`](/../master/src/Rector/Core/ExcludeServiceKeysToArrayRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

Change parameter $excludeServiceKeys explicity to an array

```diff
-GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
-ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
+GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
+ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
```

<br>

## `FindByPidsAndAuthorIdRector`

- class: [`Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository\FindByPidsAndAuthorIdRector`](/../master/src/Rector/SysNote/Domain/Repository/FindByPidsAndAuthorIdRector.php)
- [test fixtures](/../master/tests/Rector/SysNote/Domain/Repository/Fixture)

Use findByPidsAndAuthorId instead of findByPidsAndAuthor

```diff
 $sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
 $backendUser = new BackendUser();
-$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
+$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
```

<br>

## `IgnoreValidationAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\IgnoreValidationAnnotationRector`](/../master/src/Rector/Annotation/IgnoreValidationAnnotationRector.php)

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

<br>

## `InjectAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\InjectAnnotationRector`](/../master/src/Rector/Annotation/InjectAnnotationRector.php)

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

<br>

## `InjectEnvironmentServiceIfNeededInResponseRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\InjectEnvironmentServiceIfNeededInResponseRector`](/../master/src/Rector/Extbase/InjectEnvironmentServiceIfNeededInResponseRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

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

<br>

## `LazyAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\LazyAnnotationRector`](/../master/src/Rector/Annotation/LazyAnnotationRector.php)

Turns properties with `@lazy` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Lazy`

```diff
 /**
- * @lazy
+ * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
  */
-private $someProperty;
+private $someProperty;
```

<br>

## `MoveApplicationContextToEnvironmentApiRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\MoveApplicationContextToEnvironmentApiRector`](/../master/src/Rector/Core/Utility/MoveApplicationContextToEnvironmentApiRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Use Environment API to fetch application context

```diff
-GeneralUtility::getApplicationContext();
+Environment::getContext();
```

<br>

## `MoveRenderArgumentsToInitializeArgumentsMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector`](/../master/src/Rector/Fluid/ViewHelpers/MoveRenderArgumentsToInitializeArgumentsMethodRector.php)
- [test fixtures](/../master/tests/Rector/Fluid/ViewHelpers/Fixture)

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

<br>

## `RefactorDbConstantsRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\RefactorDbConstantsRector`](/../master/src/Rector/Core/RefactorDbConstantsRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

Changes TYPO3_db constants to $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'].

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

## `RefactorDeprecatedConcatenateMethodsPageRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Page\RefactorDeprecatedConcatenateMethodsPageRendererRector`](/../master/src/Rector/Core/Page/RefactorDeprecatedConcatenateMethodsPageRendererRector.php)
- [test fixtures](/../master/tests/Rector/Core/Page/Fixture)

Turns method call names to new ones.

```diff
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$files = $someObject->getConcatenateFiles();
+$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
```

<br>

## `RefactorDeprecationLogRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorDeprecationLogRector`](/../master/src/Rector/Core/Utility/RefactorDeprecationLogRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Refactor GeneralUtility deprecationLog methods

```diff
-GeneralUtility::logDeprecatedFunction();
-GeneralUtility::logDeprecatedViewHelperAttribute();
-GeneralUtility::deprecationLog('Message');
-GeneralUtility::getDeprecationLogFileName();
+trigger_error('A useful message', E_USER_DEPRECATED);
```

<br>

## `RefactorExplodeUrl2ArrayFromGeneralUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorExplodeUrl2ArrayFromGeneralUtilityRector`](/../master/src/Rector/Core/Utility/RefactorExplodeUrl2ArrayFromGeneralUtilityRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true

```diff
-$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
-$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
+parse_str('https://www.domain.com', $variable);
+$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
```

<br>

## `RefactorIdnaEncodeMethodToNativeFunctionRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorIdnaEncodeMethodToNativeFunctionRector`](/../master/src/Rector/Core/Utility/RefactorIdnaEncodeMethodToNativeFunctionRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Use native function idn_to_ascii instead of GeneralUtility::idnaEncode

```diff
-$domain = GeneralUtility::idnaEncode('domain.com');
-$email = GeneralUtility::idnaEncode('email@domain.com');
+$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
+$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
```

<br>

## `RefactorMethodsFromExtensionManagementUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorMethodsFromExtensionManagementUtilityRector`](/../master/src/Rector/Core/Utility/RefactorMethodsFromExtensionManagementUtilityRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Refactor deprecated methods from ExtensionManagementUtility.

```diff
-ExtensionManagementUtility::removeCacheFiles();
+GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
```

<br>

## `RefactorRemovedMarkerMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMarkerMethodsFromContentObjectRendererRector`](/../master/src/Rector/Frontend/ContentObject/RefactorRemovedMarkerMethodsFromContentObjectRendererRector.php)
- [test fixtures](/../master/tests/Rector/Frontend/ContentObject/Fixture)

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

<br>

## `RefactorRemovedMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector`](/../master/src/Rector/Frontend/ContentObject/RefactorRemovedMethodsFromContentObjectRendererRector.php)
- [test fixtures](/../master/tests/Rector/Frontend/ContentObject/Fixture)

Refactor removed methods from ContentObjectRenderer.

```diff
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
```

<br>

## `RefactorRemovedMethodsFromGeneralUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector`](/../master/src/Rector/Core/Utility/RefactorRemovedMethodsFromGeneralUtilityRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

Refactor removed methods from GeneralUtility.

```diff
-GeneralUtility::gif_compress();
+\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
```

<br>

## `RegisterPluginWithVendorNameRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RegisterPluginWithVendorNameRector`](/../master/src/Rector/Extbase/RegisterPluginWithVendorNameRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

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

<br>

## `RemoveColPosParameterRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization\RemoveColPosParameterRector`](/../master/src/Rector/Backend/Domain/Repository/Localization/RemoveColPosParameterRector.php)

Remove parameter colPos from methods.

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
```

<br>

## `RemoveFlushCachesRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemoveFlushCachesRector`](/../master/src/Rector/Extbase/RemoveFlushCachesRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

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

<br>

## `RemoveInitMethodFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\Page\RemoveInitMethodFromPageRepositoryRector`](/../master/src/Rector/Frontend/Page/RemoveInitMethodFromPageRepositoryRector.php)
- [test fixtures](/../master/tests/Rector/Frontend/Page/Fixture)

Remove method call init from PageRepository

```diff
-$repository = GeneralUtility::makeInstance(PageRepository::class);
-$repository->init(true);
+$repository = GeneralUtility::makeInstance(PageRepository::class);
```

<br>

## `RemoveInitTemplateMethodCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Frontend\Controller\RemoveInitTemplateMethodCallRector`](/../master/src/Rector/Frontend/Controller/RemoveInitTemplateMethodCallRector.php)
- [test fixtures](/../master/tests/Rector/Frontend/Controller/Fixture)

Remove method call initTemplate from TypoScriptFrontendController

```diff
-$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->initTemplate();
+$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
```

<br>

## `RemoveInternalAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemoveInternalAnnotationRector`](/../master/src/Rector/Extbase/RemoveInternalAnnotationRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

Remove @internal annotation from classes extending \TYPO3\CMS\Extbase\Mvc\Controller\CommandController

```diff
-/**
- * @internal
- */
 class MyCommandController extends CommandController
 {
 }
```

<br>

## `RemovePropertiesFromSimpleDataHandlerControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Controller\RemovePropertiesFromSimpleDataHandlerControllerRector`](/../master/src/Rector/Backend/Controller/RemovePropertiesFromSimpleDataHandlerControllerRector.php)
- [test fixtures](/../master/tests/Rector/Backend/Controller/Fixture)

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

<br>

## `RemovePropertyExtensionNameRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyExtensionNameRector`](/../master/src/Rector/Extbase/RemovePropertyExtensionNameRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

Use method getControllerExtensionName from $request property instead of removed property $extensionName

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

<br>

## `RemovePropertyUserAuthenticationRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyUserAuthenticationRector`](/../master/src/Rector/Extbase/RemovePropertyUserAuthenticationRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

Use method getBackendUserAuthentication instead of removed property $userAuthentication

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

<br>

## `RemoveSecondArgumentGeneralUtilityMkdirDeepRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector`](/../master/src/Rector/Core/Utility/RemoveSecondArgumentGeneralUtilityMkdirDeepRector.php)
- [test fixtures](/../master/tests/Rector/Core/Utility/Fixture)

Remove second argument of GeneralUtility::mkdir_deep()

```diff
-GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
```

<br>

## `RenameClassMapAliasRector`

- class: [`Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector`](/../master/src/Rector/Migrations/RenameClassMapAliasRector.php)
- [test fixtures](/../master/tests/Rector/Migrations/Fixture)

Replaces defined classes by new ones.

```yaml
services:
    Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector:
        oldClassAliasMap: config/Migrations/Code/ClassAliasMap.php
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

## `RenameMethodCallToEnvironmentMethodCallRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector`](/../master/src/Rector/Core/Environment/RenameMethodCallToEnvironmentMethodCallRector.php)

Turns method call names to new ones from new Environment API.

```diff
-Bootstrap::usesComposerClassLoading();
-GeneralUtility::getApplicationContext();
-EnvironmentService::isEnvironmentInCliMode();
+Environment::getContext();
+Environment::isComposerMode();
+Environment::isCli();
```

<br>

## `RenamePiListBrowserResultsRector`

- class: [`Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector`](/../master/src/Rector/IndexedSearch/Controller/RenamePiListBrowserResultsRector.php)

Rename pi_list_browseresults calls to renderPagination

```diff
-$this->pi_list_browseresults
+$this->renderPagination
```

<br>

## `SubstituteConstantParsetimeStartRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\SubstituteConstantParsetimeStartRector`](/../master/src/Rector/Core/SubstituteConstantParsetimeStartRector.php)
- [test fixtures](/../master/tests/Rector/Core/Fixture)

Substitute $GLOBALS['PARSETIME_START'] with round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000)

```diff
-$parseTime = $GLOBALS['PARSETIME_START'];
+$parseTime = round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000);
```

<br>

## `TemplateServiceSplitConfArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\TypoScript\TemplateServiceSplitConfArrayRector`](/../master/src/Rector/Core/TypoScript/TemplateServiceSplitConfArrayRector.php)
- [test fixtures](/../master/tests/Rector/Core/TypoScript/Fixture)

Substitute TemplateService->splitConfArray() with TypoScriptService->explodeConfigurationForOptionSplit()

```diff
-$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
+$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
```

<br>

## `TimeTrackerGlobalsToSingletonRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\TimeTracker\TimeTrackerGlobalsToSingletonRector`](/../master/src/Rector/Core/TimeTracker/TimeTrackerGlobalsToSingletonRector.php)
- [test fixtures](/../master/tests/Rector/Core/TimeTracker/Fixture)

Substitute $GLOBALS['TT'] method calls

```diff
-$GLOBALS['TT']->setTSlogMessage('content');
+GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
```

<br>

## `TransientAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\TransientAnnotationRector`](/../master/src/Rector/Annotation/TransientAnnotationRector.php)

Turns properties with `@transient` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Transient`

```diff
 /**
- * @transient
+ * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
  */
-private $someProperty;
+private $someProperty;
```

<br>

## `UseActionControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\UseActionControllerRector`](/../master/src/Rector/Extbase/UseActionControllerRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Fixture)

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

<br>

## `UseMetaDataAspectRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Resource\UseMetaDataAspectRector`](/../master/src/Rector/Core/Resource/UseMetaDataAspectRector.php)
- [test fixtures](/../master/tests/Rector/Core/Resource/Fixture)

Use $fileObject->getMetaData()->get() instead of $fileObject->_getMetaData()

```diff
 $fileObject = new File();
-$fileObject->_getMetaData();
+$fileObject->getMetaData()->get();
```

<br>

## `UseNativePhpHex2binMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\Extbase\Utility\UseNativePhpHex2binMethodRector`](/../master/src/Rector/Extbase/Utility/UseNativePhpHex2binMethodRector.php)
- [test fixtures](/../master/tests/Rector/Extbase/Utility/Fixture)

Turns \TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php hex2bin

```diff
-\TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br>

## `UsePackageManagerActivePackagesRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Package\UsePackageManagerActivePackagesRector`](/../master/src/Rector/Core/Package/UsePackageManagerActivePackagesRector.php)
- [test fixtures](/../master/tests/Rector/Core/Package/Fixture)

Use PackageManager API instead of $GLOBALS['TYPO3_LOADED_EXT']

```diff
-$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
+$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
```

<br>

## `UseRenderingContextGetControllerContextRector`

- class: [`Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\UseRenderingContextGetControllerContextRector`](/../master/src/Rector/Fluid/ViewHelpers/UseRenderingContextGetControllerContextRector.php)
- [test fixtures](/../master/tests/Rector/Fluid/ViewHelpers/Fixture)

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

<br>

## `UseTypo3InformationForCopyRightNoticeRector`

- class: [`Ssch\TYPO3Rector\Rector\Backend\Utility\UseTypo3InformationForCopyRightNoticeRector`](/../master/src/Rector/Backend/Utility/UseTypo3InformationForCopyRightNoticeRector.php)
- [test fixtures](/../master/tests/Rector/Backend/Utility/Fixture)

Migrate the method BackendUtility::TYPO3_copyRightNotice() to use Typo3Information API

```diff
-$copyright = BackendUtility::TYPO3_copyRightNotice();
+$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
```

<br>

## `ValidateAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\Annotation\ValidateAnnotationRector`](/../master/src/Rector/Annotation/ValidateAnnotationRector.php)

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

<br>

