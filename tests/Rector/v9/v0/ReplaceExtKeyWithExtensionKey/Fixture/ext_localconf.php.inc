<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'Stepin.'.$_EXTKEY,
    'ArticleTeaser',
    array(
        'ArticleTeaser' => 'list, listProgramCategoryAndCountryArticles, listProgramCategoryArticles, listCountryArticles',
    ),
    // non-cacheable actions
    array(
        'ArticleTeaser' => 'listProgramCategoryArticles, listCountryArticles, listCountryArticles',
    )
);

ExtensionUtility::configurePlugin(
    $_EXTKEY,
    'Pi1',
    array(
        'News' => 'index,list,latest,detail,search,searchResult,archiveMenu',
        'Category' => 'list',
    ),
    array(
        'News' => 'search,searchResult',
    )
);


// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info'][$_EXTKEY . '_pi1'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Resources/Private/Backend/class.tx_' . $_EXTKEY . '_cms_layout.php:tx_' . $_EXTKEY . '_cms_layout->getExtensionSummary';

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'Stepin.'.'_temp_fixture_easy_testing',
    'ArticleTeaser',
    array(
        'ArticleTeaser' => 'list, listProgramCategoryAndCountryArticles, listProgramCategoryArticles, listCountryArticles',
    ),
    // non-cacheable actions
    array(
        'ArticleTeaser' => 'listProgramCategoryArticles, listCountryArticles, listCountryArticles',
    )
);

ExtensionUtility::configurePlugin(
    '_temp_fixture_easy_testing',
    'Pi1',
    array(
        'News' => 'index,list,latest,detail,search,searchResult,archiveMenu',
        'Category' => 'list',
    ),
    array(
        'News' => 'search,searchResult',
    )
);


// Page module hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['_temp_fixture_easy_testing' . '_pi1']['_temp_fixture_easy_testing'] = 'EXT:' . '_temp_fixture_easy_testing' . '/Resources/Private/Backend/class.tx_' . '_temp_fixture_easy_testing' . '_cms_layout.php:tx_' . '_temp_fixture_easy_testing' . '_cms_layout->getExtensionSummary';

?>
