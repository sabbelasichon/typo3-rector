<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html\Fixture;


use TYPO3\CMS\Core\Html\RteHtmlParser;

final class RteHtmlParserRemovedMethods
{

    public function doSomething(): void
    {
        $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
        $rtehtmlparser->evalWriteFile();
    }

}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html\Fixture;


use TYPO3\CMS\Core\Html\RteHtmlParser;

final class RteHtmlParserRemovedMethods
{

    public function doSomething(): void
    {
        $rtehtmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(RteHtmlParser::class);
    }

}

?>
