<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

// must be defined
$_EXTKEY = '___';

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

// must be defined
$_EXTKEY = '___';

if ( ! defined('TYPO3_MODE')) {
    die('Access denied.');
}

ExtensionUtility::configurePlugin(
    'Stepin.'.'foo_bar_baz',
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
    'foo_bar_baz',
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
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['foo_bar_baz' . '_pi1']['foo_bar_baz'] = 'EXT:' . 'foo_bar_baz' . '/Resources/Private/Backend/class.tx_' . 'foo_bar_baz' . '_cms_layout.php:tx_' . 'foo_bar_baz' . '_cms_layout->getExtensionSummary';

?>
