<?php

use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector\Source\MyOtherFileType;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Resource\AbstractFile;

$type1 = AbstractFile::FILETYPE_UNKNOWN;
$type2 = AbstractFile::FILETYPE_TEXT;
$type3 = AbstractFile::FILETYPE_IMAGE;
$type4 = AbstractFile::FILETYPE_AUDIO;
$type5 = AbstractFile::FILETYPE_VIDEO;
$type6 = AbstractFile::FILETYPE_APPLICATION;
$otherConst = AbstractFile::OTHER_CONSTANT;
$typeFromOtherClass = MyOtherFileType::FILETYPE_UNKNOWN;

$icon1 = Icon::SIZE_DEFAULT;
$icon2 = Icon::SIZE_SMALL;
$icon3 = Icon::SIZE_MEDIUM;
$icon4 = Icon::SIZE_LARGE;
$icon5 = Icon::SIZE_MEGA;

?>
-----
<?php

use TYPO3\CMS\Core\Resource\FileType;
use TYPO3\CMS\Core\Imaging\IconSize;
use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector\Source\MyOtherFileType;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Resource\AbstractFile;

$type1 = FileType::UNKNOWN;
$type2 = FileType::TEXT;
$type3 = FileType::IMAGE;
$type4 = FileType::AUDIO;
$type5 = FileType::VIDEO;
$type6 = FileType::APPLICATION;
$otherConst = AbstractFile::OTHER_CONSTANT;
$typeFromOtherClass = MyOtherFileType::FILETYPE_UNKNOWN;

$icon1 = IconSize::DEFAULT;
$icon2 = IconSize::SMALL;
$icon3 = IconSize::MEDIUM;
$icon4 = IconSize::LARGE;
$icon5 = IconSize::MEGA;

?>
