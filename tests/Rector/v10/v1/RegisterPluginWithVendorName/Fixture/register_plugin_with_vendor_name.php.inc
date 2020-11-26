<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'TYPO3.CMS.Form',
    'Formframework',
    'Form',
    'content-form'
);

$_EXTKEY = 'Form';

ExtensionUtility::registerPlugin(
    'TYPO3.CMS.' .$_EXTKEY,
    'Formframework',
    'Form',
    'content-form'
);

ExtensionUtility::registerPlugin(
    'Form',
    'Formframework',
    'Form',
    'content-form'
);

call_user_func(
    static function ($extensionKey) {
        if (TYPO3_MODE === 'BE') {
            ExtensionUtility::registerPlugin(
                $extensionKey,
                'CardCheck',
                'Plugin'
            );

            ExtensionUtility::registerPlugin(
                $extensionKey,
                'Verify',
                'Plugin Full Name'
            );
        }
    },
    'my_package'
);

?>
-----
<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'Form',
    'Formframework',
    'Form',
    'content-form'
);

$_EXTKEY = 'Form';

ExtensionUtility::registerPlugin(
    'Form',
    'Formframework',
    'Form',
    'content-form'
);

ExtensionUtility::registerPlugin(
    'Form',
    'Formframework',
    'Form',
    'content-form'
);

call_user_func(
    static function ($extensionKey) {
        if (TYPO3_MODE === 'BE') {
            ExtensionUtility::registerPlugin(
                $extensionKey,
                'CardCheck',
                'Plugin'
            );

            ExtensionUtility::registerPlugin(
                $extensionKey,
                'Verify',
                'Plugin Full Name'
            );
        }
    },
    'my_package'
);

?>
