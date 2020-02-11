<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldGeneralUtilityMethods
{
    public function method(): void
    {
        GeneralUtility::gif_compress('theFile', 'theType');
        $theFile = GeneralUtility::png_to_gif_by_imagemagick('theFile');
        $theNewFile = GeneralUtility::read_png_gif('theFile');
        $isInArray = GeneralUtility::inArray(['1', '2'], '1');
        $newArray = GeneralUtility::removeArrayEntryByValue(['1', '2'], '1');
        $theFilteredArray = GeneralUtility::keepItemsInArray(['1', '2'], '1');
        $theArray = ['firstKey' => '1', 'secondKey' => '2'];
        GeneralUtility::remapArrayKeys($theArray, ['firstKey' => 'first', 'secondKey' => 'second']);
        $newArray = GeneralUtility::array_merge(['1'], ['2']);
        $diffArray = GeneralUtility::arrayDiffAssocRecursive(['1', '2'], ['1', '2']);
        GeneralUtility::naturalKeySortRecursive($theArray);
        GeneralUtility::cleanOutputBuffers();
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClassWithOldGeneralUtilityMethods
{
    public function method(): void
    {
        \TYPO3\CMS\Core\Imaging\GraphicalFunctions::gifCompress('theFile', 'theType');
        $theFile = \TYPO3\CMS\Core\Imaging\GraphicalFunctions::pngToGifByImagemagick('theFile');
        $theNewFile = \TYPO3\CMS\Core\Imaging\GraphicalFunctions::readPngGif('theFile');
        $isInArray = \TYPO3\CMS\Core\Utility\ArrayUtility::inArray(['1', '2'], '1');
        $newArray = \TYPO3\CMS\Core\Utility\ArrayUtility::removeArrayEntryByValue(['1', '2'], '1');
        $theFilteredArray = \TYPO3\CMS\Core\Utility\ArrayUtility::keepItemsInArray(['1', '2'], '1');
        $theArray = ['firstKey' => '1', 'secondKey' => '2'];
        \TYPO3\CMS\Core\Utility\ArrayUtility::remapArrayKeys($theArray, ['firstKey' => 'first', 'secondKey' => 'second']);
        $newArray = ['1'] + ['2'];
        $diffArray = \TYPO3\CMS\Core\Utility\ArrayUtility::arrayDiffAssocRecursive(['1', '2'], ['1', '2']);
        \TYPO3\CMS\Core\Utility\ArrayUtility::naturalKeySortRecursive($theArray);
        \TYPO3\CMS\Core\Utility\GeneralUtility::flushOutputBuffers();
    }
}

?>
