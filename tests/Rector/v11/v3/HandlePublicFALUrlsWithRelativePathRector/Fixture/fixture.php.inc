<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\HandlePublicFALUrlsWithRelativePathRector\Fixture;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TestFile implements FileInterface
{
    public function getPublicUrl(bool $relativeToCurrentScript)
    {
        return 'foo';
    }
}

class MyCustomMediaHelper implements OnlineMediaHelperInterface
{
    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        return 'foo';
    }
}


$file = GeneralUtility::makeInstance(TestFile::class);
$foo = $file->getPublicUrl(true);

$helper = GeneralUtility::makeInstance(MyCustomMediaHelper::class);
$bar = $helper->getPublicUrl($file, true);

// $event = GeneralUtility::makeInstance()

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\HandlePublicFALUrlsWithRelativePathRector\Fixture;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TestFile implements FileInterface
{
    public function getPublicUrl(bool $relativeToCurrentScript)
    {
        return 'foo';
    }
}

class MyCustomMediaHelper implements OnlineMediaHelperInterface
{
    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        return 'foo';
    }
}

$file = GeneralUtility::makeInstance(TestFile::class);
$foo = $file->getPublicUrl();

$helper = GeneralUtility::makeInstance(MyCustomMediaHelper::class);
$bar = $helper->getPublicUrl($file);

// $event = GeneralUtility::makeInstance()

?>
