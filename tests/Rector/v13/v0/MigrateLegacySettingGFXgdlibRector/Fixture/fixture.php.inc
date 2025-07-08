<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateLegacySettingGFXgdlibRector\Fixture;

if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] === true) {
    // do something
}

if (true === $GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib']) {
    // do something
}

if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] !== true) {
    // do something
}

if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] === false) {
    // do something
}

if (false === $GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib']) {
    // do something
}

if ($GLOBALS['TYPO3_CONF_VARS']['GFX']['gdlib'] !== false) {
    // do something
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateLegacySettingGFXgdlibRector\Fixture;

if (class_exists(\GdImage::class)) {
    // do something
}

if (class_exists(\GdImage::class)) {
    // do something
}

if (!class_exists(\GdImage::class)) {
    // do something
}

if (!class_exists(\GdImage::class)) {
    // do something
}

if (!class_exists(\GdImage::class)) {
    // do something
}

if (class_exists(\GdImage::class)) {
    // do something
}

?>
