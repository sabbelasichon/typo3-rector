<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lowlevel\Utility\ArrayBrowser;

class RefactorArrayBrowserWrapValue
{
    public function method(): void
    {
        $arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
        $arrayBrowser->wrapValue('value');
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Lowlevel\Utility\ArrayBrowser;

class RefactorArrayBrowserWrapValue
{
    public function method(): void
    {
        $arrayBrowser = GeneralUtility::makeInstance(ArrayBrowser::class);
        htmlspecialchars('value');
    }
}
?>
