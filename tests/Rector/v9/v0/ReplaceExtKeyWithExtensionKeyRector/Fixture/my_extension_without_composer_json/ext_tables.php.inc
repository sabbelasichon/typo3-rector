<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (TYPO3_MODE === 'BE') {
    // must be defined
    $_EXTKEY = '___';

    /**
     * Registers a Backend Module
     */
    ExtensionUtility::registerModule(
        'Stepin.' . $_EXTKEY,
        'web',   // Make module a submodule of 'tools'
        'brochure',  // Submodule key
        '',            // Position
        array(
            'BrochureOrder' => 'showForm, exportBrochures'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_export.xlf',
        )
    );
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (TYPO3_MODE === 'BE') {
    // must be defined
    $_EXTKEY = '___';

    /**
     * Registers a Backend Module
     */
    ExtensionUtility::registerModule(
        'Stepin.' . 'my_extension_without_composer_json',
        'web',   // Make module a submodule of 'tools'
        'brochure',  // Submodule key
        '',            // Position
        array(
            'BrochureOrder' => 'showForm, exportBrochures'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . 'my_extension_without_composer_json' . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . 'my_extension_without_composer_json' . '/Resources/Private/Language/locallang_export.xlf',
        )
    );
}

?>
