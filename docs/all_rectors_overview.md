# All 140 Rectors Overview

## `Array2XmlCsToArray2XmlRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\Array2XmlCsToArray2XmlRector`](/src/Rector/v8/v1/Array2XmlCsToArray2XmlRector.php)

array2xml_cs to array2xml

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;

-GeneralUtility::array2xml_cs();
+GeneralUtility::array2xml();
```

<br><br>

## `ArrayUtilityInArrayToFuncInArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v6\ArrayUtilityInArrayToFuncInArrayRector`](/src/Rector/v8/v6/ArrayUtilityInArrayToFuncInArrayRector.php)

Method inArray from ArrayUtility to `in_array`

```diff
-ArrayUtility::inArray()
+in_array
```

<br><br>

## `BackendUserAuthenticationSimplelogRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\BackendUserAuthenticationSimplelogRector`](/src/Rector/v9/v3/BackendUserAuthenticationSimplelogRector.php)

Migrate the method `BackendUserAuthentication->simplelog()` to `BackendUserAuthentication->writelog()`

```diff
 $someObject = GeneralUtility::makeInstance(TYPO3\CMS\Core\Authentication\BackendUserAuthentication::class);
-$someObject->simplelog($message, $extKey, $error);
+$someObject->writelog(4, 0, $error, 0, ($extKey ? '[' . $extKey . '] ' : '') . $message, []);
```

<br><br>

## `BackendUtilityEditOnClickRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\BackendUtilityEditOnClickRector`](/src/Rector/v10/v1/BackendUtilityEditOnClickRector.php)

Migrate the method `BackendUtility::editOnClick()` to use UriBuilder API

```diff
 $pid = 2;
 $params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
-$url = BackendUtility::editOnClick($params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
```

<br><br>

## `BackendUtilityGetModuleUrlRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\BackendUtilityGetModuleUrlRector`](/src/Rector/v9/v3/BackendUtilityGetModuleUrlRector.php)

Migrate the method `BackendUtility::getModuleUrl()` to use UriBuilder API

```diff
 $moduleName = 'record_edit';
 $params = ['pid' => 2];
-$url = BackendUtility::getModuleUrl($moduleName, $params);
+$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute($moduleName, $params);
```

<br><br>

## `BackendUtilityGetRecordRawRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordRawRector`](/src/Rector/v8/v7/BackendUtilityGetRecordRawRector.php)

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

## `BackendUtilityGetRecordsByFieldToQueryBuilderRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\BackendUtilityGetRecordsByFieldToQueryBuilderRector`](/src/Rector/v8/v7/BackendUtilityGetRecordsByFieldToQueryBuilderRector.php)

BackendUtility::getRecordsByField to QueryBuilder

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
-$rows = BackendUtility::getRecordsByField('table', 'uid', 3);
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Database\ConnectionPool;
+use TYPO3\CMS\Core\Database\Query\Restriction\BackendWorkspaceRestriction;
+use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
+$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('table');
+$queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(BackendWorkspaceRestriction::class));
+$queryBuilder->getRestrictions()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
+$queryBuilder->select('*')->from('table')->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(3)));
+$rows = $queryBuilder->execute()->fetchAll();
```

<br><br>

## `BackendUtilityGetViewDomainToPageRouterRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector`](/src/Rector/v10/v0/BackendUtilityGetViewDomainToPageRouterRector.php)

Refactor method call `BackendUtility::getViewDomain()` to PageRouter

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;
-$domain1 = BackendUtility::getViewDomain(1);
+use TYPO3\CMS\Core\Site\SiteFinder;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1);
+$domain1 = $site->getRouter()->generateUri(1);
```

<br><br>

## `BackendUtilityShortcutExistsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\BackendUtilityShortcutExistsRector`](/src/Rector/v9/v4/BackendUtilityShortcutExistsRector.php)

shortcutExists Static call replaced by method call of ShortcutRepository

```diff
-TYPO3\CMS\Backend\Utility\BackendUtility::shortcutExists($url);
+GeneralUtility::makeInstance(ShortcutRepository::class)->shortcutExists($url);
```

<br><br>

## `CallEnableFieldsFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\CallEnableFieldsFromPageRepositoryRector`](/src/Rector/v9/v4/CallEnableFieldsFromPageRepositoryRector.php)

Call enable fields from PageRepository instead of ContentObjectRenderer

```diff
 $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$contentObjectRenderer->enableFields('pages', false, []);
+GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
```

<br><br>

## `ChangeAttemptsParameterConsoleOutputRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\ChangeAttemptsParameterConsoleOutputRector`](/src/Rector/v8/v7/ChangeAttemptsParameterConsoleOutputRector.php)

Turns old default value to parameter in `ConsoleOutput->askAndValidate()` and/or `ConsoleOutput->select()` method

```diff
-$this->output->select('The question', [1, 2, 3], null, false, false);
+$this->output->select('The question', [1, 2, 3], null, false, null);
```

<br><br>

## `ChangeDefaultCachingFrameworkNamesRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ChangeDefaultCachingFrameworkNamesRector`](/src/Rector/v10/v0/ChangeDefaultCachingFrameworkNamesRector.php)

Use new default cache names like core instead of cache_core)

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

<br><br>

## `ChangeMethodCallsForStandaloneViewRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\ChangeMethodCallsForStandaloneViewRector`](/src/Rector/v8/v0/ChangeMethodCallsForStandaloneViewRector.php)

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

## `CharsetConverterToMultiByteFunctionsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\CharsetConverterToMultiByteFunctionsRector`](/src/Rector/v8/v5/CharsetConverterToMultiByteFunctionsRector.php)

Move from CharsetConverter methods to mb_string functions

```diff
-        use TYPO3\CMS\Core\Charset\CharsetConverter;
-        use TYPO3\CMS\Core\Utility\GeneralUtility;
-        $charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
-        $charsetConverter->strlen('utf-8', 'string');
+mb_strlen('string', 'utf-8');
```

<br><br>

## `CheckForExtensionInfoRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionInfoRector`](/src/Rector/v9/v0/CheckForExtensionInfoRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\CheckForExtensionVersionRector`](/src/Rector/v9/v0/CheckForExtensionVersionRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ConfigurationManagerAddControllerConfigurationMethodRector`](/src/Rector/v10/v0/ConfigurationManagerAddControllerConfigurationMethodRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\ConstantToEnvironmentCallRector`](/src/Rector/v9/v4/ConstantToEnvironmentCallRector.php)

Turns defined constant to static method call of new Environment API.

```diff
-PATH_thisScript;
+Environment::getCurrentScript();
```

<br><br>

## `ContentObjectRendererFileResourceRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\ContentObjectRendererFileResourceRector`](/src/Rector/v8/v5/ContentObjectRendererFileResourceRector.php)

Migrate fileResource method of class ContentObjectRenderer

```diff
-$template = $this->cObj->fileResource('EXT:vendor/Resources/Private/Templates/Template.html');
+$path = $GLOBALS['TSFE']->tmpl->getFileName('EXT:vendor/Resources/Private/Templates/Template.html');
+if ($path !== null && file_exists($path)) {
+    $template = file_get_contents($path);
+}
```

<br><br>

## `CopyMethodGetPidForModTSconfigRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\CopyMethodGetPidForModTSconfigRector`](/src/Rector/v9/v3/CopyMethodGetPidForModTSconfigRector.php)

`Copy` method getPidForModTSconfig of class BackendUtility over

```diff
-use TYPO3\CMS\Backend\Utility\BackendUtility;BackendUtility::getPidForModTSconfig('pages', 1, 2);
+use TYPO3\CMS\Core\Utility\MathUtility;
+
+$table = 'pages';
+$uid = 1;
+$pid = 2;
+$table === 'pages' && MathUtility::canBeInterpretedAsInteger($uid) ? $uid : $pid;
```

<br><br>

## `DataHandlerRmCommaRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerRmCommaRector`](/src/Rector/v8/v7/DataHandlerRmCommaRector.php)

Migrate the method `DataHandler::rmComma()` to use `rtrim()`

```diff
 $inList = '1,2,3,';
 $dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
-$inList = $dataHandler->rmComma(trim($inList));
+$inList = rtrim(trim($inList), ',');
```

<br><br>

## `DataHandlerVariousMethodsAndMethodArgumentsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\DataHandlerVariousMethodsAndMethodArgumentsRector`](/src/Rector/v8/v7/DataHandlerVariousMethodsAndMethodArgumentsRector.php)

Remove CharsetConvertParameters

```diff
 $dataHandler = GeneralUtility::makeInstance(DataHandler::class);
-$dest = $dataHandler->destPathFromUploadFolder('uploadFolder');
-$dataHandler->extFileFunctions('table', 'field', 'theField', 'deleteAll');
+$dest = PATH_site . 'uploadFolder';
+$dataHandler->extFileFunctions('table', 'field', 'theField');
```

<br><br>

## `DatabaseConnectionToDbalRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\DatabaseConnectionToDbalRector`](/src/Rector/v9/v0/DatabaseConnectionToDbalRector.php)

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

## `DocumentTemplateAddStyleSheetRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\DocumentTemplateAddStyleSheetRector`](/src/Rector/v9/v4/DocumentTemplateAddStyleSheetRector.php)

Use PageRenderer::addCssFile instead of `DocumentTemplate::addStyleSheet()` 

```diff
-$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
-$documentTemplate->addStyleSheet('foo', 'foo.css');
+GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('foo.css', 'stylesheet', 'screen', '');
```

<br><br>

## `ExcludeServiceKeysToArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\ExcludeServiceKeysToArrayRector`](/src/Rector/v10/v2/ExcludeServiceKeysToArrayRector.php)

Change parameter `$excludeServiceKeys` explicity to an array

```diff
-GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', 'key1, key2');
-ExtensionManagementUtility::findService('serviceType', 'serviceSubType', 'key1, key2');
+GeneralUtility::makeInstanceService('serviceType', 'serviceSubType', ['key1', 'key2']);
+ExtensionManagementUtility::findService('serviceType', 'serviceSubType', ['key1', 'key2']);
```

<br><br>

## `FindByPidsAndAuthorIdRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\FindByPidsAndAuthorIdRector`](/src/Rector/v9/v0/FindByPidsAndAuthorIdRector.php)

Use findByPidsAndAuthorId instead of findByPidsAndAuthor

```diff
 $sysNoteRepository = GeneralUtility::makeInstance(SysNoteRepository::class);
 $backendUser = new BackendUser();
-$sysNoteRepository->findByPidsAndAuthor('1,2,3', $backendUser);
+$sysNoteRepository->findByPidsAndAuthorId('1,2,3', $backendUser->getUid());
```

<br><br>

## `ForceTemplateParsingInTsfeAndTemplateServiceRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\ForceTemplateParsingInTsfeAndTemplateServiceRector`](/src/Rector/v10/v0/ForceTemplateParsingInTsfeAndTemplateServiceRector.php)
- [test fixtures](/tests/Rector/v10/v0/Fixture)

Force template parsing in tsfe is replaced with context api and aspects

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

<br><br>

## `GeneralUtilityGetUrlRequestHeadersRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\GeneralUtilityGetUrlRequestHeadersRector`](/src/Rector/v9/v2/GeneralUtilityGetUrlRequestHeadersRector.php)

Refactor `GeneralUtility::getUrl()` request headers in a associative way

```diff
-GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language: de-DE']);
+GeneralUtility::getUrl('https://typo3.org', 1, ['Content-Language' => 'de-DE']);
```

<br><br>

## `GeneralUtilityToUpperAndLowerRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\GeneralUtilityToUpperAndLowerRector`](/src/Rector/v8/v1/GeneralUtilityToUpperAndLowerRector.php)

Use `mb_strtolower` and `mb_strtoupper`

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-$toUpper = GeneralUtility::strtoupper('foo');
-$toLower = GeneralUtility::strtolower('FOO');
+$toUpper = mb_strtoupper('foo', 'utf-8');
+$toLower = mb_strtolower('FOO', 'utf-8');
```

<br><br>

## `GeneratePageTitleRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\GeneratePageTitleRector`](/src/Rector/v9/v0/GeneratePageTitleRector.php)

Use generatePageTitle of TSFE instead of class PageGenerator

```diff
 use TYPO3\CMS\Frontend\Page\PageGenerator;

-PageGenerator::generatePageTitle();
+$GLOBALS['TSFE']->generatePageTitle();
```

<br><br>

## `GetFileAbsFileNameRemoveDeprecatedArgumentsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\GetFileAbsFileNameRemoveDeprecatedArgumentsRector`](/src/Rector/v8/v0/GetFileAbsFileNameRemoveDeprecatedArgumentsRector.php)

Remove second and third argument of `GeneralUtility::getFileAbsFileName()`

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-GeneralUtility::getFileAbsFileName('foo.txt', false, true);
+GeneralUtility::getFileAbsFileName('foo.txt');
```

<br><br>

## `GetPreferredClientLanguageRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\GetPreferredClientLanguageRector`](/src/Rector/v8/v0/GetPreferredClientLanguageRector.php)

Use `Locales->getPreferredClientLanguage()` instead of `CharsetConverter::getPreferredClientLanguage()`

```diff
+use TYPO3\CMS\Core\Localization\Locales;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$preferredLanguage = $GLOBALS['TSFE']->csConvObj->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
+$preferredLanguage = GeneralUtility::makeInstance(Locales::class)->getPreferredClientLanguage(GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'));
```

<br><br>

## `GetTemporaryImageWithTextRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v1\GetTemporaryImageWithTextRector`](/src/Rector/v7/v1/GetTemporaryImageWithTextRector.php)

Use GraphicalFunctions->getTemporaryImageWithText instead of LocalImageProcessor->getTemporaryImageWithText

```diff
-GeneralUtility::makeInstance(LocalImageProcessor::class)->getTemporaryImageWithText("foo", "bar", "baz", "foo")
+GeneralUtility::makeInstance(GraphicalFunctions::class)->getTemporaryImageWithText("foo", "bar", "baz", "foo")
```

<br><br>

## `IgnoreValidationAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\IgnoreValidationAnnotationRector`](/src/Rector/v9/v0/IgnoreValidationAnnotationRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\InjectAnnotationRector`](/src/Rector/v9/v0/InjectAnnotationRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\InjectEnvironmentServiceIfNeededInResponseRector`](/src/Rector/v10/v2/InjectEnvironmentServiceIfNeededInResponseRector.php)

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

## `InstantiatePageRendererExplicitlyRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\InstantiatePageRendererExplicitlyRector`](/src/Rector/v7/v4/InstantiatePageRendererExplicitlyRector.php)

Instantiate PageRenderer explicitly

```diff
-$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
+$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
```

<br><br>

## `MetaTagManagementRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\MetaTagManagementRector`](/src/Rector/v9/v0/MetaTagManagementRector.php)

Use setMetaTag method from PageRenderer class

```diff
 use TYPO3\CMS\Core\Page\PageRenderer;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$pageRenderer->addMetaTag('<meta name="keywords" content="seo, search engine optimisation, search engine optimization, search engine ranking">');
+$pageRenderer->setMetaTag('name', 'keywords', 'seo, search engine optimisation, search engine optimization, search engine ranking');
```

<br><br>

## `MethodReadLLFileToLocalizationFactoryRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\MethodReadLLFileToLocalizationFactoryRector`](/src/Rector/v7/v4/MethodReadLLFileToLocalizationFactoryRector.php)

Use LocalizationFactory->getParsedData instead of GeneralUtility::readLLfile

```diff
+use TYPO3\CMS\Core\Localization\LocalizationFactory;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$locallangs = GeneralUtility::readLLfile('EXT:foo/locallang.xml', 'de');
+$locallangs = GeneralUtility::makeInstance(LocalizationFactory::class)->getParsedData('EXT:foo/locallang.xml', 'de');
```

<br><br>

## `MoveApplicationContextToEnvironmentApiRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\MoveApplicationContextToEnvironmentApiRector`](/src/Rector/v10/v2/MoveApplicationContextToEnvironmentApiRector.php)

Use Environment API to fetch application context

```diff
-GeneralUtility::getApplicationContext();
+Environment::getContext();
```

<br><br>

## `MoveLanguageFilesFromExtensionLangRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\MoveLanguageFilesFromExtensionLangRector`](/src/Rector/v9/v3/MoveLanguageFilesFromExtensionLangRector.php)

Move language resources from ext:lang to their new locations

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.no_title');
+$languageService->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.no_title');
```

<br><br>

## `MoveLanguageFilesFromLocallangToResourcesRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v5\MoveLanguageFilesFromLocallangToResourcesRector`](/src/Rector/v8/v5/MoveLanguageFilesFromLocallangToResourcesRector.php)

Move language files from EXT:lang/locallang_* to Resources/Private/Language

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:lang/locallang_alt_doc.xlf:label.confirm.delete_record.title');
+$languageService->sL('LLL:EXT:lang/Resources/Private/Language/locallang_alt_doc.xlf:label.confirm.delete_record.title');
```

<br><br>

## `MoveLanguageFilesFromRemovedCmsExtensionRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v4\MoveLanguageFilesFromRemovedCmsExtensionRector`](/src/Rector/v7/v4/MoveLanguageFilesFromRemovedCmsExtensionRector.php)

Move language files of removed cms to new location

```diff
 use TYPO3\CMS\Core\Localization\LanguageService;
 $languageService = new LanguageService();
-$languageService->sL('LLL:EXT:cms/web_info/locallang.xlf:pages_1');
+$languageService->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_webinfo.xlf:pages_1');
```

<br><br>

## `MoveRenderArgumentsToInitializeArgumentsMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\MoveRenderArgumentsToInitializeArgumentsMethodRector`](/src/Rector/v9/v0/MoveRenderArgumentsToInitializeArgumentsMethodRector.php)

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

## `PageNotFoundAndErrorHandlingRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\PageNotFoundAndErrorHandlingRector`](/src/Rector/v9/v2/PageNotFoundAndErrorHandlingRector.php)

Page Not Found And Error handling in Frontend

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

<br><br>

## `PhpOptionsUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\PhpOptionsUtilityRector`](/src/Rector/v9/v3/PhpOptionsUtilityRector.php)

Refactor methods from PhpOptionsUtility

```diff
-PhpOptionsUtility::isSessionAutoStartEnabled()
+filter_var(ini_get('session.auto_start'), FILTER_VALIDATE_BOOLEAN, [FILTER_REQUIRE_SCALAR, FILTER_NULL_ON_FAILURE])
```

<br><br>

## `PrependAbsolutePathToGetFileAbsFileNameRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\PrependAbsolutePathToGetFileAbsFileNameRector`](/src/Rector/v8/v0/PrependAbsolutePathToGetFileAbsFileNameRector.php)

Use `GeneralUtility::getFileAbsFileName()` instead of `GraphicalFunctions->prependAbsolutePath()`

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

<br><br>

## `PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector`](/src/Rector/v9/v3/PropertyUserTsToMethodGetTsConfigOfBackendUserAuthenticationRector.php)

Use method getTSConfig instead of property userTS

```diff
-if(is_array($GLOBALS['BE_USER']->userTS['tx_news.']) && $GLOBALS['BE_USER']->userTS['tx_news.']['singleCategoryAcl'] === '1') {
+if(is_array($GLOBALS['BE_USER']->getTSConfig()['tx_news.']) && $GLOBALS['BE_USER']->getTSConfig()['tx_news.']['singleCategoryAcl'] === '1') {
     return true;
 }
```

<br><br>

## `RandomMethodsToRandomClassRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RandomMethodsToRandomClassRector`](/src/Rector/v8/v0/RandomMethodsToRandomClassRector.php)

Deprecated random generator methods in GeneralUtility

```diff
+use TYPO3\CMS\Core\Crypto\Random;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-$randomBytes = GeneralUtility::generateRandomBytes();
-$randomHex = GeneralUtility::getRandomHexString();
+$randomBytes = GeneralUtility::makeInstance(Random::class)->generateRandomBytes();
+$randomHex = GeneralUtility::makeInstance(Random::class)->generateRandomHexString();
```

<br><br>

## `RefactorArrayBrowserWrapValueRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorArrayBrowserWrapValueRector`](/src/Rector/v8/v7/RefactorArrayBrowserWrapValueRector.php)

Migrate the method `ArrayBrowser->wrapValue()` to use `htmlspecialchars()`

```diff
 $arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
-$arrayBrowser->wrapValue('value');
+htmlspecialchars('value');
```

<br><br>

## `RefactorBackendUtilityGetPagesTSconfigRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorBackendUtilityGetPagesTSconfigRector`](/src/Rector/v9/v0/RefactorBackendUtilityGetPagesTSconfigRector.php)

Refactor method getPagesTSconfig of class BackendUtility if possible

```diff
 use TYPO3\CMS\Backend\Utility\BackendUtility;
-$pagesTsConfig = BackendUtility::getPagesTSconfig(1, $rootLine = null, $returnPartArray = true);
+$pagesTsConfig = BackendUtility::getRawPagesTSconfig(1, $rootLine = null);
```

<br><br>

## `RefactorDbConstantsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\RefactorDbConstantsRector`](/src/Rector/v8/v1/RefactorDbConstantsRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RefactorDeprecatedConcatenateMethodsPageRendererRector`](/src/Rector/v9/v4/RefactorDeprecatedConcatenateMethodsPageRendererRector.php)

Turns method call names to new ones.

```diff
 $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
-$files = $someObject->getConcatenateFiles();
+$files = array_merge($this->getConcatenateCss(), $this->getConcatenateJavascript());
```

<br><br>

## `RefactorDeprecationLogRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorDeprecationLogRector`](/src/Rector/v9/v0/RefactorDeprecationLogRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RefactorExplodeUrl2ArrayFromGeneralUtilityRector`](/src/Rector/v9/v4/RefactorExplodeUrl2ArrayFromGeneralUtilityRector.php)

Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function `parse_str` if it is true

```diff
-$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
-$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
+parse_str('https://www.domain.com', $variable);
+$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
```

<br><br>

## `RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector`](/src/Rector/v8/v7/RefactorGraphicalFunctionsTempPathAndCreateTemSubDirRector.php)

Refactor `tempPath()` and createTempSubDir on GraphicalFunctions

```diff
 $graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
-$graphicalFunctions->createTempSubDir('var/transient/');
-return $graphicalFunctions->tempPath . 'var/transient/';
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/var/transient/');
+return 'typo3temp/' . 'var/transient/';
```

<br><br>

## `RefactorIdnaEncodeMethodToNativeFunctionRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RefactorIdnaEncodeMethodToNativeFunctionRector`](/src/Rector/v10/v0/RefactorIdnaEncodeMethodToNativeFunctionRector.php)

Use native function `idn_to_ascii` instead of GeneralUtility::idnaEncode

```diff
-$domain = GeneralUtility::idnaEncode('domain.com');
-$email = GeneralUtility::idnaEncode('email@domain.com');
+$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
+$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
```

<br><br>

## `RefactorInternalPropertiesOfTSFERector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\RefactorInternalPropertiesOfTSFERector`](/src/Rector/v10/v1/RefactorInternalPropertiesOfTSFERector.php)

Refactor Internal public TSFE properties

```diff

```

<br><br>

## `RefactorMethodFileContentRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\RefactorMethodFileContentRector`](/src/Rector/v8/v3/RefactorMethodFileContentRector.php)

Refactor method fileContent of class TemplateService

```diff
-$content = $GLOBALS['TSFE']->tmpl->fileContent('foo.txt');
+$content = $GLOBALS['TSFE']->tmpl->getFileName('foo.txt') ? file_get_contents('foo.txt') : null;
```

<br><br>

## `RefactorMethodsFromExtensionManagementUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RefactorMethodsFromExtensionManagementUtilityRector`](/src/Rector/v9/v0/RefactorMethodsFromExtensionManagementUtilityRector.php)

Refactor deprecated methods from ExtensionManagementUtility.

```diff
-ExtensionManagementUtility::removeCacheFiles();
+GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesInGroup('system');
```

<br><br>

## `RefactorPrintContentMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorPrintContentMethodsRector`](/src/Rector/v8/v7/RefactorPrintContentMethodsRector.php)

Refactor printContent methods of classes TaskModuleController and PageLayoutController

```diff
 use TYPO3\CMS\Backend\Controller\PageLayoutController;
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-use TYPO3\CMS\Taskcenter\Controller\TaskModuleController;
+use TYPO3\CMS\Core\Utility\GeneralUtility;use TYPO3\CMS\Taskcenter\Controller\TaskModuleController;
 $pageLayoutController = GeneralUtility::makeInstance(PageLayoutController::class);
-$pageLayoutController->printContent();
-
+echo $pageLayoutController->getModuleTemplate()->renderContent();
 $taskLayoutController = GeneralUtility::makeInstance(TaskModuleController::class);
-$taskLayoutController->printContent();
+echo $taskLayoutController->content;
```

<br><br>

## `RefactorProcessOutputRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RefactorProcessOutputRector`](/src/Rector/v9/v5/RefactorProcessOutputRector.php)

`TypoScriptFrontendController->processOutput()` to `TypoScriptFrontendController->applyHttpHeadersToResponse()` and `TypoScriptFrontendController->processContentForOutput()`

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

 $tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->processOutput();
+$tsfe->applyHttpHeadersToResponse();
+$tsfe->processContentForOutput();
```

<br><br>

## `RefactorPropertiesOfTypoScriptFrontendControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RefactorPropertiesOfTypoScriptFrontendControllerRector`](/src/Rector/v9/v5/RefactorPropertiesOfTypoScriptFrontendControllerRector.php)

Refactor some properties of TypoScriptFrontendController

```diff
-$previewBeUserUid = $GLOBALS['TSFE']->ADMCMD_preview_BEUSER_uid;
-$workspacePreview = $GLOBALS['TSFE']->workspacePreview;
-$loginAllowedInBranch = $GLOBALS['TSFE']->loginAllowedInBranch;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Context\Context;
+$previewBeUserUid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('backend.user', 'id', 0);
+$workspacePreview = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);
+$loginAllowedInBranch = $GLOBALS['TSFE']->checkIfLoginAllowedInBranch();
```

<br><br>

## `RefactorQueryViewTableWrapRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v3\RefactorQueryViewTableWrapRector`](/src/Rector/v8/v3/RefactorQueryViewTableWrapRector.php)

Migrate the method `QueryView->tableWrap()` to use pre-Tag

```diff
 $queryView = GeneralUtility::makeInstance(QueryView::class);
-$output = $queryView->tableWrap('value');
+$output = '<pre>' . 'value' . '</pre>';
```

<br><br>

## `RefactorRemovedMarkerMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\RefactorRemovedMarkerMethodsFromContentObjectRendererRector`](/src/Rector/v8/v7/RefactorRemovedMarkerMethodsFromContentObjectRendererRector.php)

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

## `RefactorRemovedMarkerMethodsFromHtmlParserRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMarkerMethodsFromHtmlParserRector`](/src/Rector/v8/v0/RefactorRemovedMarkerMethodsFromHtmlParserRector.php)

Refactor removed Marker-related methods from HtmlParser.

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

<br><br>

## `RefactorRemovedMethodsFromContentObjectRendererRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromContentObjectRendererRector`](/src/Rector/v8/v0/RefactorRemovedMethodsFromContentObjectRendererRector.php)

Refactor removed methods from ContentObjectRenderer.

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
 $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
-$cObj->RECORDS(['tables' => 'tt_content', 'source' => '1,2,3']);
+$cObj->cObjGetSingle('RECORDS', ['tables' => 'tt_content', 'source' => '1,2,3']);
```

<br><br>

## `RefactorRemovedMethodsFromGeneralUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RefactorRemovedMethodsFromGeneralUtilityRector`](/src/Rector/v8/v0/RefactorRemovedMethodsFromGeneralUtilityRector.php)

Refactor removed methods from GeneralUtility.

```diff
-GeneralUtility::gif_compress();
+\TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress();
```

<br><br>

## `RefactorTsConfigRelatedMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\RefactorTsConfigRelatedMethodsRector`](/src/Rector/v9/v3/RefactorTsConfigRelatedMethodsRector.php)

Refactor TSconfig related methods

```diff
-$hasFilterBox = !$GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.hideFilter');
+$hasFilterBox = !($GLOBALS['BE_USER']->getTSConfig()['options.']['pageTree.']['hideFilter.'] ?? null);
```

<br><br>

## `RefactorVariousGeneralUtilityMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\RefactorVariousGeneralUtilityMethodsRector`](/src/Rector/v8/v1/RefactorVariousGeneralUtilityMethodsRector.php)

Refactor various deprecated methods of class GeneralUtility

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
 $url = 'https://www.domain.com/';
-$url = GeneralUtility::rawUrlEncodeFP($url);
+$url = str_replace('%2F', '/', rawurlencode($url));
```

<br><br>

## `RegisterPluginWithVendorNameRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\RegisterPluginWithVendorNameRector`](/src/Rector/v10/v1/RegisterPluginWithVendorNameRector.php)

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

## `RemoveCharsetConverterParametersRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveCharsetConverterParametersRector`](/src/Rector/v8/v0/RemoveCharsetConverterParametersRector.php)

Remove CharsetConvertParameters

```diff
 $charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
-$charsetConvert->entities_to_utf8('string', false);
-$charsetConvert->utf8_to_numberarray('string', false, false);
+$charsetConvert->entities_to_utf8('string');
+$charsetConvert->utf8_to_numberarray('string');
```

<br><br>

## `RemoveColPosParameterRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\RemoveColPosParameterRector`](/src/Rector/v9/v3/RemoveColPosParameterRector.php)

Remove parameter colPos from methods.

```diff
 $someObject = GeneralUtility::makeInstance(LocalizationRepository::class);
-$someObject->fetchOriginLanguage($pageId, $colPos, $localizedLanguage);
+$someObject->fetchOriginLanguage($pageId, $localizedLanguage);
```

<br><br>

## `RemoveFlushCachesRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveFlushCachesRector`](/src/Rector/v9/v5/RemoveFlushCachesRector.php)

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

## `RemoveFormatConstantsEmailFinisherRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemoveFormatConstantsEmailFinisherRector`](/src/Rector/v10/v0/RemoveFormatConstantsEmailFinisherRector.php)

Remove constants FORMAT_PLAINTEXT and FORMAT_HTML of class TYPO3\CMS\Form\Domain\Finishers\EmailFinisher

```diff
-$this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
+$this->setOption('addHtmlPart', true);
```

<br><br>

## `RemoveInitMethodFromPageRepositoryRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveInitMethodFromPageRepositoryRector`](/src/Rector/v9/v5/RemoveInitMethodFromPageRepositoryRector.php)

Remove method call init from PageRepository

```diff
-$repository = GeneralUtility::makeInstance(PageRepository::class);
-$repository->init(true);
+$repository = GeneralUtility::makeInstance(PageRepository::class);
```

<br><br>

## `RemoveInitMethodGraphicalFunctionsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodGraphicalFunctionsRector`](/src/Rector/v9/v4/RemoveInitMethodGraphicalFunctionsRector.php)

Remove method call init of class GraphicalFunctions

```diff
 use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
-$graphicalFunctions->init();
+$graphicalFunctions = GeneralUtility::makeInstance(GraphicalFunctions::class);
```

<br><br>

## `RemoveInitMethodTemplateServiceRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitMethodTemplateServiceRector`](/src/Rector/v9/v4/RemoveInitMethodTemplateServiceRector.php)

Remove method call init of class TemplateService

```diff
 use TYPO3\CMS\Core\TypoScript\TemplateService;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$templateService = GeneralUtility::makeInstance(TemplateService::class);
-$templateService->init();
+$templateService = GeneralUtility::makeInstance(TemplateService::class);
```

<br><br>

## `RemoveInitTemplateMethodCallRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\RemoveInitTemplateMethodCallRector`](/src/Rector/v9/v4/RemoveInitTemplateMethodCallRector.php)

Remove method call initTemplate from TypoScriptFrontendController

```diff
-$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
-$tsfe->initTemplate();
+$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
```

<br><br>

## `RemoveInternalAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\RemoveInternalAnnotationRector`](/src/Rector/v9/v5/RemoveInternalAnnotationRector.php)

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

## `RemoveLangCsConvObjAndParserFactoryRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveLangCsConvObjAndParserFactoryRector`](/src/Rector/v8/v0/RemoveLangCsConvObjAndParserFactoryRector.php)

Remove CsConvObj and ParserFactory from LanguageService::class and `$GLOBALS['lang']`

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

<br><br>

## `RemoveMethodCallConnectDbRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallConnectDbRector`](/src/Rector/v7/v0/RemoveMethodCallConnectDbRector.php)

Remove `EidUtility::connectDB()` call

```diff
-EidUtility::connectDB()
```

<br><br>

## `RemoveMethodCallLoadTcaRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\RemoveMethodCallLoadTcaRector`](/src/Rector/v7/v0/RemoveMethodCallLoadTcaRector.php)

Remove `GeneralUtility::loadTCA()` call

```diff
-GeneralUtility::loadTCA()
```

<br><br>

## `RemoveMethodInitTCARector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveMethodInitTCARector`](/src/Rector/v9/v0/RemoveMethodInitTCARector.php)

Remove superfluous EidUtility::initTCA call

```diff
-use TYPO3\CMS\Frontend\Utility\EidUtility;
-EidUtility::initTCA();
```

<br><br>

## `RemovePropertiesFromSimpleDataHandlerControllerRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemovePropertiesFromSimpleDataHandlerControllerRector`](/src/Rector/v9/v0/RemovePropertiesFromSimpleDataHandlerControllerRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\RemovePropertyExtensionNameRector`](/src/Rector/v10/v0/RemovePropertyExtensionNameRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemovePropertyUserAuthenticationRector`](/src/Rector/v8/v0/RemovePropertyUserAuthenticationRector.php)

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

## `RemoveRteHtmlParserEvalWriteFileRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveRteHtmlParserEvalWriteFileRector`](/src/Rector/v8/v0/RemoveRteHtmlParserEvalWriteFileRector.php)

remove evalWriteFile method from RteHtmlparser.

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

<br><br>

## `RemoveSecondArgumentGeneralUtilityMkdirDeepRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\RemoveSecondArgumentGeneralUtilityMkdirDeepRector`](/src/Rector/v9/v0/RemoveSecondArgumentGeneralUtilityMkdirDeepRector.php)

Remove second argument of `GeneralUtility::mkdir_deep()`

```diff
-GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/', 'myfolder');
+GeneralUtility::mkdir_deep(PATH_site . 'typo3temp/' . 'myfolder');
```

<br><br>

## `RemoveWakeupCallFromEntityRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RemoveWakeupCallFromEntityRector`](/src/Rector/v8/v0/RemoveWakeupCallFromEntityRector.php)

Remove __wakeup call for AbstractDomainObject

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v2\RenameMethodCallToEnvironmentMethodCallRector`](/src/Rector/v9/v2/RenameMethodCallToEnvironmentMethodCallRector.php)

Turns method call names to new ones from new Environment API.

```diff
-Bootstrap::usesComposerClassLoading();
-GeneralUtility::getApplicationContext();
-EnvironmentService::isEnvironmentInCliMode();
+Environment::isComposerMode();
+Environment::getContext();
+Environment::isCli();
```

<br><br>

## `RenamePiListBrowserResultsRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\RenamePiListBrowserResultsRector`](/src/Rector/v7/v6/RenamePiListBrowserResultsRector.php)

Rename pi_list_browseresults calls to renderPagination

```diff
-$this->pi_list_browseresults
+$this->renderPagination
```

<br><br>

## `ReplaceAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector`](/src/Rector/v9/v0/ReplaceAnnotationRector.php)

Replace old annotation by new one

```php
<?php

declare(strict_types=1);

use Ssch\TYPO3Rector\Rector\v9\v0\ReplaceAnnotationRector;
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

## `RequireMethodsToNativeFunctionsRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RequireMethodsToNativeFunctionsRector`](/src/Rector/v8/v0/RequireMethodsToNativeFunctionsRector.php)

Refactor GeneralUtility::requireOnce and GeneralUtility::requireFile

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-
-GeneralUtility::requireOnce('somefile.php');
-GeneralUtility::requireFile('some_other_file.php');
+require_once 'somefile.php';
+require 'some_other_file.php';
```

<br><br>

## `RteHtmlParserRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\RteHtmlParserRector`](/src/Rector/v8/v0/RteHtmlParserRector.php)

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

## `SendNotifyEmailToMailApiRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v1\SendNotifyEmailToMailApiRector`](/src/Rector/v10/v1/SendNotifyEmailToMailApiRector.php)

Refactor ContentObjectRenderer::sendNotifyEmail to MailMessage-API

```diff
-$GLOBALS['TSFE']->cObj->sendNotifyEmail("Subject\nMessage", 'max.mustermann@domain.com', 'max.mustermann@domain.com', 'max.mustermann@domain.com');
+use Symfony\Component\Mime\Address;
+use TYPO3\CMS\Core\Mail\MailMessage;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\MailUtility;$success = false;
+$mail = GeneralUtility::makeInstance(MailMessage::class);
+$message = trim("Subject\nMessage");
+$senderName = trim(null);
+$senderAddress = trim('max.mustermann@domain.com');
+if ($senderAddress !== '') {
+    $mail->from(new Address($senderAddress, $senderName));
+}
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

<br><br>

## `SetSystemLocaleFromSiteLanguageRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\SetSystemLocaleFromSiteLanguageRector`](/src/Rector/v10/v0/SetSystemLocaleFromSiteLanguageRector.php)

Refactor `TypoScriptFrontendController->settingLocale()` to `Locales::setSystemLocaleFromSiteLanguage()`

```diff
-
+use TYPO3\CMS\Core\Localization\Locales;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

 $controller = GeneralUtility::makeInstance(TypoScriptFrontendController::class, null, 0, 0);
-$controller->settingLocale();
+Locales::setSystemLocaleFromSiteLanguage($controller->getLanguage());
```

<br><br>

## `SubstituteCacheWrapperMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\SubstituteCacheWrapperMethodsRector`](/src/Rector/v9/v0/SubstituteCacheWrapperMethodsRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\SubstituteConstantParsetimeStartRector`](/src/Rector/v9/v0/SubstituteConstantParsetimeStartRector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\SubstituteResourceFactoryRector`](/src/Rector/v10/v3/SubstituteResourceFactoryRector.php)

Substitue `ResourceFactory::getInstance()` through GeneralUtility::makeInstance(ResourceFactory::class)

```diff
-$resourceFactory = ResourceFactory::getInstance();
+$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
```

<br><br>

## `SystemEnvironmentBuilderConstantsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\SystemEnvironmentBuilderConstantsRector`](/src/Rector/v9/v4/SystemEnvironmentBuilderConstantsRector.php)

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

## `TcaMigrationRector`

- class: [`Ssch\TYPO3Rector\Rector\Core\Tca\TcaMigrationRector`](/src/Rector/Core/Tca/TcaMigrationRector.php)

This Rector migrates the TCA configuration for all configurations in separate files in folder TCA\Configuration. This is done on runtime via core migration classes \TYPO3\CMS\Core\Migrations\TcaMigration for different versions

```diff
-return [
-    'ctrl' => [
-        'divider2tabs' => true,
-    ],
-    'columns' => [
-        'sys_language_uid' => [
-        ],
-    ],
-];
+return ['ctrl' => ['divider2tabs' => true], 'columns' => ['sys_language_uid' => ['config' => ['type' => 'none']]]];
```

<br><br>

## `TemplateGetFileNameToFilePathSanitizerRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\TemplateGetFileNameToFilePathSanitizerRector`](/src/Rector/v9/v4/TemplateGetFileNameToFilePathSanitizerRector.php)

Use `FilePathSanitizer->sanitize()` instead of `TemplateService->getFileName()`

```diff
-$fileName = $GLOBALS['TSFE']->tmpl->getFileName('foo.text');
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Frontend\Resource\FilePathSanitizer;
+use TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException;
+use TYPO3\CMS\Core\Resource\Exception\InvalidPathException;
+use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
+use TYPO3\CMS\Core\Resource\Exception\InvalidFileException;
+use TYPO3\CMS\Core\TimeTracker\TimeTracker;
+try {
+    $fileName = GeneralUtility::makeInstance(FilePathSanitizer::class)->sanitize((string) 'foo.text');
+} catch (InvalidFileNameException $e) {
+    $fileName = null;
+} catch (InvalidPathException|FileDoesNotExistException|InvalidFileException $e) {
+    $fileName = null;
+    if ($GLOBALS['TSFE']->tmpl->tt_track) {
+        GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage($e->getMessage(), 3);
+    }
+}
```

<br><br>

## `TemplateServiceSplitConfArrayRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\TemplateServiceSplitConfArrayRector`](/src/Rector/v8/v7/TemplateServiceSplitConfArrayRector.php)

Substitute `TemplateService->splitConfArray()` with `TypoScriptService->explodeConfigurationForOptionSplit()`

```diff
-$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
+$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
```

<br><br>

## `TimeTrackerGlobalsToSingletonRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerGlobalsToSingletonRector`](/src/Rector/v8/v0/TimeTrackerGlobalsToSingletonRector.php)

Substitute `$GLOBALS['TT']` method calls

```diff
-$GLOBALS['TT']->setTSlogMessage('content');
+GeneralUtility::makeInstance(TimeTracker::class)->setTSlogMessage('content');
```

<br><br>

## `TimeTrackerInsteadOfNullTimeTrackerRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v0\TimeTrackerInsteadOfNullTimeTrackerRector`](/src/Rector/v8/v0/TimeTrackerInsteadOfNullTimeTrackerRector.php)

Use class TimeTracker instead of NullTimeTracker

```diff
-use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
+use TYPO3\CMS\Core\TimeTracker\TimeTracker;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
-$timeTracker1 = new NullTimeTracker();
-$timeTracker2 = GeneralUtility::makeInstance(NullTimeTracker::class);
+$timeTracker1 = new TimeTracker(false);
+$timeTracker2 = GeneralUtility::makeInstance(TimeTracker::class, false);
```

<br><br>

## `TypeHandlingServiceToTypeHandlingUtilityRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v0\TypeHandlingServiceToTypeHandlingUtilityRector`](/src/Rector/v7/v0/TypeHandlingServiceToTypeHandlingUtilityRector.php)

Use TypeHandlingUtility instead of TypeHandlingService

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
-use TYPO3\CMS\Extbase\Service\TypeHandlingService;
-GeneralUtility::makeInstance(TypeHandlingService::class)->isSimpleType('string');
+use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
+TypeHandlingUtility::isSimpleType('string');
```

<br><br>

## `TypoScriptFrontendControllerCharsetConverterRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v1\TypoScriptFrontendControllerCharsetConverterRector`](/src/Rector/v8/v1/TypoScriptFrontendControllerCharsetConverterRector.php)

Refactor `$TSFE->csConvObj` and `$TSFE->csConv()`

```diff
-$output = $GLOBALS['TSFE']->csConvObj->conv_case('utf-8', 'foobar', 'lower');
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Charset\CharsetConverter;
+$charsetConverter = GeneralUtility::makeInstance(CharsetConverter::class);
+$output = $charsetConverter->conv_case('utf-8', 'foobar', 'lower');
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

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\UseActionControllerRector`](/src/Rector/v10/v2/UseActionControllerRector.php)

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

## `UseAddJsFileInsteadOfLoadJavascriptLibRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseAddJsFileInsteadOfLoadJavascriptLibRector`](/src/Rector/v9/v4/UseAddJsFileInsteadOfLoadJavascriptLibRector.php)

Use method addJsFile of class PageRenderer instead of method loadJavascriptLib of class ModuleTemplate

```diff
 use TYPO3\CMS\Backend\Template\ModuleTemplate;
+use TYPO3\CMS\Core\Page\PageRenderer;
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 $moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
-$moduleTemplate->loadJavascriptLib('sysext/backend/Resources/Public/JavaScript/md5.js');
+GeneralUtility::makeInstance(PageRenderer::class)->addJsFile('sysext/backend/Resources/Public/JavaScript/md5.js');
```

<br><br>

## `UseCachingFrameworkInsteadGetAndStoreHashRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v7\UseCachingFrameworkInsteadGetAndStoreHashRector`](/src/Rector/v8/v7/UseCachingFrameworkInsteadGetAndStoreHashRector.php)

Use the Caching Framework directly instead of methods PageRepository::getHash and PageRepository::storeHash

```diff
-$GLOBALS['TSFE']->sys_page->storeHash('hash', ['foo', 'bar', 'baz'], 'ident');
-$hashContent2 = $GLOBALS['TSFE']->sys_page->getHash('hash');
+use TYPO3\CMS\Core\Cache\CacheManager;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->set('hash', ['foo', 'bar', 'baz'], ['ident_' . 'ident'], 0);
+$hashContent = GeneralUtility::makeInstance(CacheManager::class)->getCache('cache_hash')->get('hash');
```

<br><br>

## `UseClassSchemaInsteadReflectionServiceMethodsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseClassSchemaInsteadReflectionServiceMethodsRector`](/src/Rector/v9/v4/UseClassSchemaInsteadReflectionServiceMethodsRector.php)

Instead of fetching reflection data via ReflectionService use ClassSchema directly

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

<br><br>

## `UseClassTypo3InformationRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3InformationRector`](/src/Rector/v10/v3/UseClassTypo3InformationRector.php)

Use class Typo3Information

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

<br><br>

## `UseClassTypo3VersionRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v3\UseClassTypo3VersionRector`](/src/Rector/v10/v3/UseClassTypo3VersionRector.php)

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

## `UseContextApiForVersioningWorkspaceIdRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiForVersioningWorkspaceIdRector`](/src/Rector/v9/v4/UseContextApiForVersioningWorkspaceIdRector.php)

Use context API instead of versioningWorkspaceId

```diff
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
 $workspaceId = null;
-$workspaceId = $workspaceId ?? $GLOBALS['TSFE']->sys_page->versioningWorkspaceId;
+$workspaceId = $workspaceId ?? GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('workspace', 'id', 0);

 $GLOBALS['TSFE']->sys_page->versioningWorkspaceId = 1;
```

<br><br>

## `UseContextApiRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseContextApiRector`](/src/Rector/v9/v4/UseContextApiRector.php)

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

## `UseControllerClassesInExtbasePluginsAndModulesRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseControllerClassesInExtbasePluginsAndModulesRector`](/src/Rector/v10/v0/UseControllerClassesInExtbasePluginsAndModulesRector.php)

Use controller classes when registering extbase plugins/modules

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

<br><br>

## `UseExtPrefixForTcaIconFileRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v5\UseExtPrefixForTcaIconFileRector`](/src/Rector/v7/v5/UseExtPrefixForTcaIconFileRector.php)

Deprecate relative path to extension directory and using filename only in TCA ctrl iconfile

```diff
 return [
     'ctrl' => [
-        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('foo').'Resources/Public/Icons/image.png',
+        'iconfile' => 'EXT:foo/Resources/Public/Icons/image.png',
     ],
 ];
```

<br><br>

## `UseExtensionConfigurationApiRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseExtensionConfigurationApiRector`](/src/Rector/v9/v0/UseExtensionConfigurationApiRector.php)

Use the new ExtensionConfiguration API instead of `$GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo']`

```diff
-$extensionConfiguration2 = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['foo'], ['allowed_classes' => false]);
+use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+$extensionConfiguration2 = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('foo');
```

<br><br>

## `UseFileGetContentsForGetUrlRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v4\UseFileGetContentsForGetUrlRector`](/src/Rector/v10/v4/UseFileGetContentsForGetUrlRector.php)

Rewirte Method Calls of GeneralUtility::getUrl("somefile.csv") to @file_get_contents

```diff
-use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Http\RequestFactory;

-GeneralUtility::getUrl('some.csv');
+@file_get_contents('some.csv');
 $externalUrl = 'https://domain.com';
-GeneralUtility::getUrl($externalUrl);
+GeneralUtility::makeInstance(RequestFactory::class)->request($externalUrl)->getBody()->getContents();
```

<br><br>

## `UseGetMenuInsteadOfGetFirstWebPageRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseGetMenuInsteadOfGetFirstWebPageRector`](/src/Rector/v9/v4/UseGetMenuInsteadOfGetFirstWebPageRector.php)

Use method getMenu instead of getFirstWebPage

```diff
-$theFirstPage = $GLOBALS['TSFE']->sys_page->getFirstWebPage(0);
+$rootLevelPages = $GLOBALS['TSFE']->sys_page->getMenu(0, 'uid', 'sorting', '', false);
+if (!empty($rootLevelPages)) {
+    $theFirstPage = reset($rootLevelPages);
+}
```

<br><br>

## `UseHtmlSpecialCharsDirectlyForTranslationRector`

- class: [`Ssch\TYPO3Rector\Rector\v8\v2\UseHtmlSpecialCharsDirectlyForTranslationRector`](/src/Rector/v8/v2/UseHtmlSpecialCharsDirectlyForTranslationRector.php)

`htmlspecialchars` directly to properly escape the content.

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

<br><br>

## `UseLanguageAspectForTsfeLanguagePropertiesRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseLanguageAspectForTsfeLanguagePropertiesRector`](/src/Rector/v9/v4/UseLanguageAspectForTsfeLanguagePropertiesRector.php)

Use LanguageAspect instead of language properties of TSFE

```diff
-$languageUid = $GLOBALS['TSFE']->sys_language_uid;
+use TYPO3\CMS\Core\Context\Context;
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+$languageUid = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();
```

<br><br>

## `UseLogMethodInsteadOfNewLog2Rector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseLogMethodInsteadOfNewLog2Rector`](/src/Rector/v9/v0/UseLogMethodInsteadOfNewLog2Rector.php)

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

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseMetaDataAspectRector`](/src/Rector/v10/v0/UseMetaDataAspectRector.php)

Use `$fileObject->getMetaData()->get()` instead of `$fileObject->_getMetaData()`

```diff
 $fileObject = new File();
-$fileObject->_getMetaData();
+$fileObject->getMetaData()->get();
```

<br><br>

## `UseMethodGetPageShortcutDirectlyFromSysPageRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\UseMethodGetPageShortcutDirectlyFromSysPageRector`](/src/Rector/v9/v3/UseMethodGetPageShortcutDirectlyFromSysPageRector.php)

Use method getPageShortcut directly from PageRepository

```diff
-$GLOBALS['TSFE']->getPageShortcut('shortcut', 1, 1);
+$GLOBALS['TSFE']->sys_page->getPageShortcut('shortcut', 1, 1);
```

<br><br>

## `UseNativePhpHex2binMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseNativePhpHex2binMethodRector`](/src/Rector/v10/v0/UseNativePhpHex2binMethodRector.php)

Turns \TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin calls to native php `hex2bin`

```diff
-\TYPO3\CMS\Extbase\Utility\TypeHandlingUtility::hex2bin("6578616d706c65206865782064617461");
+hex2bin("6578616d706c65206865782064617461");
```

<br><br>

## `UseNewComponentIdForPageTreeRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseNewComponentIdForPageTreeRector`](/src/Rector/v9/v0/UseNewComponentIdForPageTreeRector.php)

Use TYPO3/CMS/Backend/PageTree/PageTreeElement instead of typo3-pagetree

```diff
 \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
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

<br><br>

## `UsePackageManagerActivePackagesRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v5\UsePackageManagerActivePackagesRector`](/src/Rector/v9/v5/UsePackageManagerActivePackagesRector.php)

Use PackageManager API instead of `$GLOBALS['TYPO3_LOADED_EXT']`

```diff
-$extensionList = $GLOBALS['TYPO3_LOADED_EXT'];
+$extensionList = GeneralUtility::makeInstance(PackageManager::class)->getActivePackages();
```

<br><br>

## `UseRenderingContextGetControllerContextRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v0\UseRenderingContextGetControllerContextRector`](/src/Rector/v9/v0/UseRenderingContextGetControllerContextRector.php)

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

## `UseRootlineUtilityInsteadOfGetRootlineMethodRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseRootlineUtilityInsteadOfGetRootlineMethodRector`](/src/Rector/v9/v4/UseRootlineUtilityInsteadOfGetRootlineMethodRector.php)

Use class RootlineUtility instead of method getRootLine

```diff
-$rootline = $GLOBALS['TSFE']->sys_page->getRootLine(1);
+use TYPO3\CMS\Core\Utility\GeneralUtility;
+use TYPO3\CMS\Core\Utility\RootlineUtility;
+$rootline = GeneralUtility::makeInstance(RootlineUtility::class, 1)->get();
```

<br><br>

## `UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector`](/src/Rector/v9/v4/UseSignalAfterExtensionInstallInsteadOfHasInstalledExtensionsRector.php)

Use the signal afterExtensionInstall of class InstallUtility

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
-use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;
+use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
 $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
-$signalSlotDispatcher->connect(
-        ExtensionManagementService::class,
-        'hasInstalledExtensions',
+    $signalSlotDispatcher->connect(
+        InstallUtility::class,
+        'afterExtensionInstall',
         \stdClass::class,
         'foo'
     );
```

<br><br>

## `UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v4\UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector`](/src/Rector/v9/v4/UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector.php)

Use the signal tablesDefinitionIsBeingBuilt of class SqlExpectedSchemaService

```diff
 use TYPO3\CMS\Core\Utility\GeneralUtility;
 use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
-use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
+use TYPO3\CMS\Install\Service\SqlExpectedSchemaService;
 $signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
-$signalSlotDispatcher->connect(
-        InstallUtility::class,
+    $signalSlotDispatcher->connect(
+        SqlExpectedSchemaService::class,
         'tablesDefinitionIsBeingBuilt',
         \stdClass::class,
         'foo'
     );
```

<br><br>

## `UseTwoLetterIsoCodeFromSiteLanguageRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v0\UseTwoLetterIsoCodeFromSiteLanguageRector`](/src/Rector/v10/v0/UseTwoLetterIsoCodeFromSiteLanguageRector.php)

The usage of the propery sys_language_isocode is deprecated. Use method getTwoLetterIsoCode of SiteLanguage

```diff
-if ($GLOBALS['TSFE']->sys_language_isocode) {
-    $GLOBALS['LANG']->init($GLOBALS['TSFE']->sys_language_isocode);
+if ($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode()) {
+    $GLOBALS['LANG']->init($GLOBALS['TSFE']->getLanguage()->getTwoLetterIsoCode());
 }
```

<br><br>

## `UseTypo3InformationForCopyRightNoticeRector`

- class: [`Ssch\TYPO3Rector\Rector\v10\v2\UseTypo3InformationForCopyRightNoticeRector`](/src/Rector/v10/v2/UseTypo3InformationForCopyRightNoticeRector.php)

Migrate the method `BackendUtility::TYPO3_copyRightNotice()` to use Typo3Information API

```diff
-$copyright = BackendUtility::TYPO3_copyRightNotice();
+$copyright = GeneralUtility::makeInstance(Typo3Information::class)->getCopyrightNotice();
```

<br><br>

## `ValidateAnnotationRector`

- class: [`Ssch\TYPO3Rector\Rector\v9\v3\ValidateAnnotationRector`](/src/Rector/v9/v3/ValidateAnnotationRector.php)

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

## `WrapClickMenuOnIconRector`

- class: [`Ssch\TYPO3Rector\Rector\v7\v6\WrapClickMenuOnIconRector`](/src/Rector/v7/v6/WrapClickMenuOnIconRector.php)

Use method wrapClickMenuOnIcon of class BackendUtility

```diff
-DocumentTemplate->wrapClickMenuOnIcon
+BackendUtility::wrapClickMenuOnIcon()
```

<br><br>

