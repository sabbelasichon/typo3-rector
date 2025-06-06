<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $pageId = 1;
        $parameters['row'] = [];
        $parameters['table'] = 'foo';
        $parameters['field'] = 'bar';
        $pageTsConfig = [];

        $dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);

        $foo = 'bar';

        $dataProviderContext
            ->setPageId($pageId)
            ->setData($parameters['row'])
            ->setTableName($parameters['table'])
            ->setFieldName($parameters['field'])
            ->setPageTsConfig($pageTsConfig);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MyClass
{
    public function myMethod(): void
    {
        $pageId = 1;
        $parameters['row'] = [];
        $parameters['table'] = 'foo';
        $parameters['field'] = 'bar';
        $pageTsConfig = [];

        $dataProviderContext = new DataProviderContext(pageId: $pageId, tableName: $parameters['table'], fieldName: $parameters['field'], data: $parameters['row'], pageTsConfig: $pageTsConfig);

        $foo = 'bar';
    }
}
?>
