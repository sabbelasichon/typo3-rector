<?php

namespace Rector\CodeQuality\General\RemoveTypo3VersionChecksRector\Fixture;

use TYPO3\CMS\Core\Information\Typo3Version;

if ((new Typo3Version())->getMajorVersion() >= 13) {
    $typo3_13_1 = true;
} else {
    $removeMe = true;
}

if ((new Typo3Version())->getMajorVersion() === 13) {
    $typo3_13_2 = true;
} else {
    $removeMe = true;
}

if ((new Typo3Version())->getMajorVersion() <= 12) {
    $typo3_12 = true;
} else {
    $typo3_13_3 = true;
}

if ((new Typo3Version())->getMajorVersion() <= 11) {
    $typo3_11 = true;
} else {
    $version_12_or_13 = true;
}

$iconSize = (new Typo3Version())->getMajorVersion() >= 13 ? 'big' : 'small';

if ((new Typo3Version())->getMajorVersion() >= 14) {
    $typo3_14_1 = true;
} else {
    $keep_me = true;
}

if ((new Typo3Version())->getMajorVersion() >= 13) {
    $typo3_13_4 = true;
}

if ((new Typo3Version())->getMajorVersion() < 13) {
    $delete_me = true;
}

$version_13 = (new Typo3Version())->getMajorVersion();
if ($version_13 >= 13) {
    $this_is_too_dangerous_to_remove = true;
}
?>
-----
<?php

namespace Rector\CodeQuality\General\RemoveTypo3VersionChecksRector\Fixture;

use TYPO3\CMS\Core\Information\Typo3Version;

$typo3_13_1 = true;

$typo3_13_2 = true;

$typo3_13_3 = true;

$version_12_or_13 = true;

$iconSize = 'big';

if ((new Typo3Version())->getMajorVersion() >= 14) {
    $typo3_14_1 = true;
} else {
    $keep_me = true;
}
$typo3_13_4 = true;

$version_13 = (new Typo3Version())->getMajorVersion();
if ($version_13 >= 13) {
    $this_is_too_dangerous_to_remove = true;
}
?>
