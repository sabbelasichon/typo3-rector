<?php

use TYPO3\CMS\IndexedSearch\Controller\SearchFormController;

final class ExtendedSearchFormController extends SearchFormController
{
    public function method(): void
    {
        $browseBox2 = $this->pi_list_browseresults(0, '', '', -1);
    }
}

?>
-----
<?php

use TYPO3\CMS\IndexedSearch\Controller\SearchFormController;

final class ExtendedSearchFormController extends SearchFormController
{
    public function method(): void
    {
        $browseBox2 = $this->renderPagination(0, '', '', -1);
    }
}

?>
