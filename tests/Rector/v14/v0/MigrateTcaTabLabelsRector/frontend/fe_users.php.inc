<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    username,password,usergroup,lastlogin,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:fe_users.tabs.personalData,
                    company, title, name, --palette--;;2, address, zip, city, country, telephone, fax, email, www, image,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    disable,--palette--;;timeRestriction,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                    description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
            ',
            'creationOptions' => [
                'enableDirectRecordTypeCreation' => false,
            ],
        ],
    ],
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateTcaTabLabelsRector\Fixture;

return [
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    username,password,usergroup,lastlogin,
                --div--;core.form.tabs:personaldata,
                    company, title, name, --palette--;;2, address, zip, city, country, telephone, fax, email, www, image,
                --div--;core.form.tabs:access,
                    disable,--palette--;;timeRestriction,
                --div--;core.form.tabs:notes,
                    description,
                --div--;core.form.tabs:extended,
            ',
            'creationOptions' => [
                'enableDirectRecordTypeCreation' => false,
            ],
        ],
    ],
];

?>
