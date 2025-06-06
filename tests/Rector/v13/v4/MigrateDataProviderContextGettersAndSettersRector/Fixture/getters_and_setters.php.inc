<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;

class MyClass
{
    public function getters(): void
    {
        $dataProviderContext = new DataProviderContext();
        $pageId = $dataProviderContext->getPageId();
        $tableName = $dataProviderContext->getTableName();
        $fieldName = $dataProviderContext->getFieldName();
        $data = $dataProviderContext->getData();
        $pageTsConfig = $dataProviderContext->getPageTsConfig();
    }

    public function setters(): void
    {
        $dataProviderContext = new DataProviderContext();
        $dataProviderContext->setPageId(1);
        $dataProviderContext->setTableName('table');
        $dataProviderContext->setFieldName('field');
        $dataProviderContext->setData([]);
        $dataProviderContext->setPageTsConfig([]);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\Fixture;

use TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext;

class MyClass
{
    public function getters(): void
    {
        $dataProviderContext = new DataProviderContext();
        $pageId = $dataProviderContext->pageId;
        $tableName = $dataProviderContext->tableName;
        $fieldName = $dataProviderContext->fieldName;
        $data = $dataProviderContext->data;
        $pageTsConfig = $dataProviderContext->pageTsConfig;
    }

    public function setters(): void
    {
        $dataProviderContext = new DataProviderContext();
        $dataProviderContext->pageId = 1;
        $dataProviderContext->tableName = 'table';
        $dataProviderContext->fieldName = 'field';
        $dataProviderContext->data = [];
        $dataProviderContext->pageTsConfig = [];
    }
}
?>
