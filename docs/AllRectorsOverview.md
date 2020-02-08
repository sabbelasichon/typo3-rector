# All 39 Rectors Overview


### `BackendUtilityEditOnClickRector`

- class: `Ssch\TYPO3Rector\Rector\Backend\Utility\BackendUtilityEditOnClickRector`

Migrate the method BackendUtility::editOnClick() to use UriBuilder API

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br>

### `CallEnableFieldsFromPageRepositoryRector`

- class: `Ssch\TYPO3Rector\Rector\Frontend\ContentObject\CallEnableFieldsFromPageRepositoryRector`

Call enable fields from PageRepository instead of ContentObjectRenderer

```diff
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->enableFields('pages', false, []);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
```

<br>

### `CascadeAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\CascadeAnnotationRector`

Turns properties with `@cascade` to properties with `@TYPO3\CMS\Extbase\Annotation\ORM\Cascade`

```diff
 /**
- * @cascade
+ * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade
  */
-private $someProperty;
+private $someProperty;
```

<br>

### `ChangeAttemptsParameterConsoleOutputRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\ChangeAttemptsParameterConsoleOutputRector`

Turns old default value to parameter in ConsoleOutput->askAndValidate() and/or ConsoleOutput->select() method

```diff
-$this->output->select('The question', [1, 2, 3], null, false, false);
+$this->output->select('The question', [1, 2, 3], null, false, null);
```

<br>

### `ChangeMethodCallsForStandaloneViewRector`

- class: `Ssch\TYPO3Rector\Rector\Fluid\View\ChangeMethodCallsForStandaloneViewRector`

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

### `CheckForExtensionInfoRector`

- class: `Ssch\TYPO3Rector\Rector\Core\CheckForExtensionInfoRector`

Change the extensions to check for info instead of info_pagetsconfig.

```diff
-if(ExtensionManagementUtility::isLoaded('info_pagetsconfig')) {

 }

 $packageManager = GeneralUtility::makeInstance(PackageManager::class);
-if($packageManager->isActive('info_pagetsconfig')) {
+if($packageManager->isActive('info')) {

 }
```

<br>

### `ConfigurationManagerAddControllerConfigurationMethodRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\ConfigurationManagerAddControllerConfigurationMethodRector`

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

### `ConstantToEnvironmentCallRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector`

Turns defined constant to static method call of new Environment API.

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
```

<br>

### `DataHandlerRmCommaRector`

- class: `Ssch\TYPO3Rector\Rector\Core\DataHandling\DataHandlerRmCommaRector`

Migrate the method DataHandler::rmComma() to use rtrim()

```diff
 $inList = '1,2,3,';
 $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
-$inList = $dataHandler->rmComma(trim($inList));
+$inList = rtrim(trim($inList), ',');
```

<br>

### `FindByPidsAndAuthorIdRector`

- class: `Ssch\TYPO3Rector\Rector\SysNote\Domain\Repository\FindByPidsAndAuthorIdRector`

Use findByPidsAndAuthorId instead of findByPidsAndAuthor

```diff
 $sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
 $backendUser = new BackendUser();
-$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
+$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
```

<br>

### `IgnoreValidationAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\IgnoreValidationAnnotationRector`

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

### `InjectAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\InjectAnnotationRector`

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

### `LazyAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\LazyAnnotationRector`

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

### `MoveRenderArgumentsToInitializeArgumentsMethodRector`

- class: `Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\MoveRenderArgumentsToInitializeArgumentsMethodRector`

Move render method arguments to initializeArguments method

```diff
 class MyViewHelper implements ViewHelperInterface
 {
-    public function render(array $firstParameter, string $secondParameter = null)
+    public function initializeArguments()
+    {
+        $this->registerArgument('firstParameter', 'array', '', true);
+        $this->registerArgument('secondParameter', 'string', '', false, null);
+    }
+
+    public function render()
+    {
+        $firstParameter = $this->arguments['firstParameter'];
+        $secondParameter = $this->arguments['secondParameter'];
+    }
 }
```

<br>

### `RefactorDeprecatedConcatenateMethodsPageRendererRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Page\RefactorDeprecatedConcatenateMethodsPageRendererRector`

Turns method call names to new ones.

```diff
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$files = $someObject->getConcatenateFiles();
+$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
```

<br>

### `RefactorExplodeUrl2ArrayFromGeneralUtilityRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Utility\RefactorExplodeUrl2ArrayFromGeneralUtilityRector`

Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true

```diff
-$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
-$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
+parse_str('https://www.domain.com', $variable);
+$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
```

<br>

### `RefactorMethodsFromExtensionManagementUtilityRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Utility\RefactorMethodsFromExtensionManagementUtilityRector`

Refactor deprecated methods from ExtensionManagementUtility.

```diff
-ExtensionManagementUtility::removeCacheFiles();
+GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
```

<br>

### `RefactorRemovedMarkerMethodsFromContentObjectRendererRector`

- class: `Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMarkerMethodsFromContentObjectRendererRector`

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

### `RefactorRemovedMethodsFromContentObjectRendererRector`

- class: `Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector`

Refactor removed methods from ContentObjectRenderer.

```diff
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
```

<br>

### `RefactorRemovedMethodsFromGeneralUtilityRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Utility\RefactorRemovedMethodsFromGeneralUtilityRector`

Refactor removed methods from GeneralUtility.

```diff
-GeneralUtility::gif_compress();
+\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
```

<br>

### `RegisterPluginWithVendorNameRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\RegisterPluginWithVendorNameRector`

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

### `RemoveColPosParameterRector`

- class: `Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization\RemoveColPosParameterRector`

Remove parameter colPos from methods.

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
```

<br>

### `RemoveFlushCachesRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\RemoveFlushCachesRector`

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

### `RemoveInitTemplateMethodCallRector`

- class: `Ssch\TYPO3Rector\Rector\Frontend\Controller\RemoveInitTemplateMethodCallRector`

Remove method call initTemplate from TypoScriptFrontendController

```diff
-$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->initTemplate();
+$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
```

<br>

### `RemoveInternalAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\RemoveInternalAnnotationRector`

Remove @internal annotation from classes extending \TYPO3\CMS\Extbase\Mvc\Controller\CommandController

```diff
-/**
- * @internal
- */
 class MyCommandController extends CommandController
 {
-}
+}
```

<br>

### `RemovePropertyExtensionNameRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyExtensionNameRector`

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

### `RemovePropertyUserAuthenticationRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\RemovePropertyUserAuthenticationRector`

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

### `RemoveSecondArgumentGeneralUtilityMkdirDeepRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Utility\RemoveSecondArgumentGeneralUtilityMkdirDeepRector`

Remove second argument of GeneralUtility::mkdir_deep()

```diff
-GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
```

<br>

### `RenameClassMapAliasRector`

- class: `Ssch\TYPO3Rector\Rector\Migrations\RenameClassMapAliasRector`

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

### `RenameMethodCallToEnvironmentMethodCallRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Environment\RenameMethodCallToEnvironmentMethodCallRector`

Turns method call names to new ones from new Environment API.

```diff
-Bootstrap::usesComposerClassLoading();
-GeneralUtility::getApplicationContext();
+Environment::getContext();
+Environment::isComposerMode();
```

<br>

### `RenamePiListBrowserResultsRector`

- class: `Ssch\TYPO3Rector\Rector\IndexedSearch\Controller\RenamePiListBrowserResultsRector`

Rename pi_list_browseresults calls to renderPagination

```diff
-$this->pi_list_browseresults
+$this->renderPagination
```

<br>

### `SubstituteConstantParsetimeStartRector`

- class: `Ssch\TYPO3Rector\Rector\Core\SubstituteConstantParsetimeStartRector`

Substitute $GLOBALS['PARSETIME_START'] with round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000)

```diff
-$parseTime = $GLOBALS['PARSETIME_START'];
+$parseTime = round($GLOBALS['TYPO3_MISC']['microtime_start'] * 1000);
```

<br>

### `TemplateServiceSplitConfArrayRector`

- class: `Ssch\TYPO3Rector\Rector\Core\TypoScript\TemplateServiceSplitConfArrayRector`

Substitute TemplateService->splitConfArray() with TypoScriptService->explodeConfigurationForOptionSplit()

```diff
-$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
+$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
```

<br>

### `TimeTrackerGlobalsToSingletonRector`

- class: `Ssch\TYPO3Rector\Rector\Core\TimeTracker\TimeTrackerGlobalsToSingletonRector`

Substitute $GLOBALS['TT'] method calls

```diff
-$GLOBALS['TT']->setTSlogMessage('content');
+GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
```

<br>

### `TransientAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\TransientAnnotationRector`

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

### `UseNativePhpHex2binMethodRector`

- class: `Ssch\TYPO3Rector\Rector\Extbase\Utility\UseNativePhpHex2binMethodRector`

Turns \TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php hex2bin

```diff
-\TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br>

### `UsePackageManagerActivePackagesRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Package\UsePackageManagerActivePackagesRector`

Use PackageManager API instead of $GLOBALS['TYPO3_LOADED_EXT']

```diff
-$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
+$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
```

<br>

### `UseRenderingContextGetControllerContextRector`

- class: `Ssch\TYPO3Rector\Rector\Fluid\ViewHelpers\UseRenderingContextGetControllerContextRector`

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

### `ValidateAnnotationRector`

- class: `Ssch\TYPO3Rector\Rector\Annotation\ValidateAnnotationRector`

Turns properties with `@validate` to properties with `@TYPO3\CMS\Extbase\Annotation\Validate`

```diff
 /**
- * @validate NotEmpty
- * @validate StringLength(minimum=0, maximum=255)
+ * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
+ * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 3, "maximum": 50})
  */
-private $someProperty;
+private $someProperty;
```

<br>

