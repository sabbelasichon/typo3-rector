<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

class PageDoktypeRegistry
{
    protected array $pageTypes = [
        PageRepository::DOKTYPE_BE_USER_SECTION => [
            'allowedTables' => '*',
        ],
        PageRepository::DOKTYPE_SYSFOLDER => [
            'allowedTables' => '*',
        ],
        PageRepository::DOKTYPE_RECYCLER => [
            'allowedTables' => 'sys_file',
        ],
        'default' => [
            'allowedTables' => 'pages,sys_category,sys_file_reference,sys_file_collection',
            'onlyAllowedTables' => false,
        ],
    ];
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\RemoveConstantPageRepositoryDoktypeRecyclerRector\Fixture;

use TYPO3\CMS\Domain\Repository\PageRepository;

class PageDoktypeRegistry
{
    protected array $pageTypes = [
        PageRepository::DOKTYPE_BE_USER_SECTION => [
            'allowedTables' => '*',
        ],
        PageRepository::DOKTYPE_SYSFOLDER => [
            'allowedTables' => '*',
        ],
        'default' => [
            'allowedTables' => 'pages,sys_category,sys_file_reference,sys_file_collection',
            'onlyAllowedTables' => false,
        ],
    ];
}
?>
