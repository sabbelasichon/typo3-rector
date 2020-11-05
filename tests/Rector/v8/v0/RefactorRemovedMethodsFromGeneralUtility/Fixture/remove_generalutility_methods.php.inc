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

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
final class ClassWithOldGeneralUtilityMethods
{
    public function method(): void
    {
        GraphicalFunctions::gifCompress('theFile', 'theType');
        $theFile = GraphicalFunctions::pngToGifByImagemagick('theFile');
        $theNewFile = GraphicalFunctions::readPngGif('theFile');
        $isInArray = ArrayUtility::inArray(['1', '2'], '1');
        $newArray = ArrayUtility::removeArrayEntryByValue(['1', '2'], '1');
        $theFilteredArray = ArrayUtility::keepItemsInArray(['1', '2'], '1');
        $theArray = ['firstKey' => '1', 'secondKey' => '2'];
        ArrayUtility::remapArrayKeys($theArray, ['firstKey' => 'first', 'secondKey' => 'second']);
        $newArray = ['1'] + ['2'];
        $diffArray = ArrayUtility::arrayDiffAssocRecursive(['1', '2'], ['1', '2']);
        ArrayUtility::naturalKeySortRecursive($theArray);
        GeneralUtility::flushOutputBuffers();
    }
}
