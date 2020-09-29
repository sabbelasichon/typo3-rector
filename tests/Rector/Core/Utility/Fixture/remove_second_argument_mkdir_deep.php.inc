<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::mkdir_deep(PATH_site.'typo3temp/', 'myfolder');
GeneralUtility::mkdir_deep(PATH_site.'typo3temp/');
GeneralUtility::mkdir_deep(PATH_site.'typo3temp/', PATH_site.'myfolder');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/'.'typo3temp/' . 'myfolder');
GeneralUtility::mkdir_deep(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/'.'typo3temp/');
\TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/'.'typo3temp/' . (\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/'.'myfolder'));

?>
