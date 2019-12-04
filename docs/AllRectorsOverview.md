# All 19 Rectors Overview

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

### `ConstantToEnvironmentCallRector`

- class: `Ssch\TYPO3Rector\Rector\Core\Environment\ConstantToEnvironmentCallRector`

Turns defined constant to static method call of new Environment API.

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
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

### `RefactorRemovedMethodsFromContentObjectRendererRector`

- class: `Ssch\TYPO3Rector\Rector\Frontend\ContentObject\RefactorRemovedMethodsFromContentObjectRendererRector`

Refactor removed methods from ContentObjectRenderer.

```diff
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
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

### `RemoveColPosParameterRector`

- class: `Ssch\TYPO3Rector\Rector\Backend\Domain\Repository\Localization\RemoveColPosParameterRector`

Remove parameter colPos from methods.

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
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

### `UnderscoreToNamespaceRector`

- class: `Ssch\TYPO3Rector\Rector\Migrations\UnderscoreToNamespaceRector`

Replaces defined classes by new ones.

```yaml
services:
    Ssch\TYPO3Rector\Rector\Migrations\UnderscoreToNamespaceRector:
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
