<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Form\Controller\FormFrontendController;

$_EXTKEY = 'Form';

ExtensionUtility::configurePlugin(
    'TICH.TichPinacoteca',
    'Foo',
    ['Mostra' => 'render, perform'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'TYPO3.CMS.'.$_EXTKEY,
    'Formframework',
    ['FormFrontend' => 'render, perform'],
    ['FormFrontend' => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'TYPO3.CMS.form',
    'Formframework',
    ['FormFrontend' => 'render, perform'],
    ['FormFrontend' => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'FriendsOfTYPO3.tt_address',
    'ListView',
    [
        'Address' => 'list,show',
    ]
);

ExtensionUtility::configurePlugin(
    'FriendsOfTYPO3.tt_address',
    'ListView',
    [
        'Address' => 'list,show',
    ],
    [
        'Address' => '',
    ]
);

ExtensionUtility::configurePlugin(
    'TYPO3.CMS.form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

call_user_func(
    static function ($extensionKey) {
        ExtensionUtility::configurePlugin(
            'TYPO3.CMS.'.$extensionKey,
            'Formframework',
            ['FormFrontend' => 'render, perform'],
            ['FormFrontend' => 'perform'],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );
    }, ('Form')
);

call_user_func(function ($extKey) {
    ExtensionUtility::configurePlugin(
        'Enw.'.$extKey,
        'Messages',
        [
            'Message' => 'list, show, ajaxReloadTeaserResults',
        ],
        // non-cacheable actions
        [
            'Message' => 'ajaxReloadTeaserResults',
        ]
    );
},
    $_EXTKEY
);

?>
-----
<?php

use TICH\TichPinacoteca\Controller\MostraController;
use FriendsOfTYPO3\TtAddress\Controller\AddressController;
use Enw\TempFixtureEasyTesting\Controller\MessageController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Form\Controller\FormFrontendController;

$_EXTKEY = 'Form';

ExtensionUtility::configurePlugin(
    'TichPinacoteca',
    'Foo',
    [MostraController::class => 'render, perform'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'TtAddress',
    'ListView',
    [
        AddressController::class => 'list,show',
    ]
);

ExtensionUtility::configurePlugin(
    'TtAddress',
    'ListView',
    [
        AddressController::class => 'list,show',
    ],
    [
        AddressController::class => '',
    ]
);

ExtensionUtility::configurePlugin(
    'Form',
    'Formframework',
    [FormFrontendController::class => 'render, perform'],
    [FormFrontendController::class => 'perform'],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

call_user_func(
    static function ($extensionKey) {
        ExtensionUtility::configurePlugin(
            'TempFixtureEasyTesting',
            'Formframework',
            [\TYPO3\CMS\TempFixtureEasyTesting\Controller\FormFrontendController::class => 'render, perform'],
            [\TYPO3\CMS\TempFixtureEasyTesting\Controller\FormFrontendController::class => 'perform'],
            ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );
    }, ('Form')
);

call_user_func(function ($extKey) {
    ExtensionUtility::configurePlugin(
        'TempFixtureEasyTesting',
        'Messages',
        [
            MessageController::class => 'list, show, ajaxReloadTeaserResults',
        ],
        // non-cacheable actions
        [
            MessageController::class => 'ajaxReloadTeaserResults',
        ]
    );
},
    $_EXTKEY
);

?>
