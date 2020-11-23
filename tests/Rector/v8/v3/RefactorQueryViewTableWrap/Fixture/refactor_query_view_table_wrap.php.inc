<?php

use TYPO3\CMS\Core\Database\QueryView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorQueryViewTableWrap
{
    public function method(): void
    {
        $queryView = GeneralUtility::makeInstance(QueryView::class);
        $output = $queryView->tableWrap('value');
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\Database\QueryView;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RefactorQueryViewTableWrap
{
    public function method(): void
    {
        $queryView = GeneralUtility::makeInstance(QueryView::class);
        $output = '<pre>' . 'value' . '</pre>';
    }
}
?>
