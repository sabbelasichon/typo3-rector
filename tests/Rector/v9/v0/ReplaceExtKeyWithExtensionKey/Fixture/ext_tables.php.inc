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

    /**
     * Registers a Backend Module
     */
    ExtensionUtility::registerModule(
        'Stepin.' . $_EXTKEY,
        'web',   // Make module a submodule of 'tools'
        'coupon',  // Submodule key
        'bottom',            // Position
        array(
            'Coupon' => 'start, saveStepOne, stepTwo, saveStepTwo, stepThree, saveStepThree, startExport, exportCoupons'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_coupon.xlf',
        )
    );
}

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (TYPO3_MODE === 'BE') {
    // must be defined
    '_temp_fixture_easy_testing' = '___';

    /**
     * Registers a Backend Module
     */
    ExtensionUtility::registerModule(
        'Stepin.' . '_temp_fixture_easy_testing',
        'web',   // Make module a submodule of 'tools'
        'brochure',  // Submodule key
        '',            // Position
        array(
            'BrochureOrder' => 'showForm, exportBrochures'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . '_temp_fixture_easy_testing' . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . '_temp_fixture_easy_testing' . '/Resources/Private/Language/locallang_export.xlf',
        )
    );

    /**
     * Registers a Backend Module
     */
    ExtensionUtility::registerModule(
        'Stepin.' . '_temp_fixture_easy_testing',
        'web',   // Make module a submodule of 'tools'
        'coupon',  // Submodule key
        'bottom',            // Position
        array(
            'Coupon' => 'start, saveStepOne, stepTwo, saveStepTwo, stepThree, saveStepThree, startExport, exportCoupons'
        ),
        array(
            'access' => 'user,group',
            'icon' => 'EXT:' . '_temp_fixture_easy_testing' . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . '_temp_fixture_easy_testing' . '/Resources/Private/Language/locallang_coupon.xlf',
        )
    );
}

?>
