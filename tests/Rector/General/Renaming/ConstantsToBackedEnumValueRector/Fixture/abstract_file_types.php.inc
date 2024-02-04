<?php

use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector\Source\MyOtherFileType;
use TYPO3\CMS\Core\Resource\AbstractFile;

$type1 = AbstractFile::FILETYPE_UNKNOWN;
$type2 = AbstractFile::FILETYPE_TEXT;
$type3 = AbstractFile::FILETYPE_IMAGE;
$type4 = AbstractFile::FILETYPE_AUDIO;
$type5 = AbstractFile::FILETYPE_VIDEO;
$type5 = AbstractFile::FILETYPE_APPLICATION;
$otherConst = AbstractFile::OTHER_CONSTANT;
$typeFromOtherClass = MyOtherFileType::FILETYPE_UNKNOWN;

?>
-----
<?php

use TYPO3\CMS\Core\Resource\FileType;
use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\ConstantsToBackedEnumValueRector\Source\MyOtherFileType;
use TYPO3\CMS\Core\Resource\AbstractFile;

$type1 = FileType::UNKNOWN->value;
$type2 = FileType::TEXT->value;
$type3 = FileType::IMAGE->value;
$type4 = FileType::AUDIO->value;
$type5 = FileType::VIDEO->value;
$type5 = FileType::APPLICATION->value;
$otherConst = AbstractFile::OTHER_CONSTANT;
$typeFromOtherClass = MyOtherFileType::FILETYPE_UNKNOWN;

?>
