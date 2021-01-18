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

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Form\Controller\FormFrontendController;

$_EXTKEY = 'Form';

ExtensionUtility::configurePlugin(
    'TichPinacoteca',
    'Foo',
    [\TICH\TichPinacoteca\Controller\MostraController::class => 'render, perform'],
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
        \FriendsOfTYPO3\TtAddress\Controller\AddressController::class => 'list,show',
    ]
);

ExtensionUtility::configurePlugin(
    'TtAddress',
    'ListView',
    [
        \FriendsOfTYPO3\TtAddress\Controller\AddressController::class => 'list,show',
    ],
    [
        \FriendsOfTYPO3\TtAddress\Controller\AddressController::class => '',
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

?>
