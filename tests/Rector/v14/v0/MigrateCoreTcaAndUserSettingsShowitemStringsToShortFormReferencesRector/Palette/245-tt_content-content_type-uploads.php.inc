<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['tt_content']['palettes']['uploadslayout'] = [
    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.uploads_layout',
    'showitem' => '
        filelink_size;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:filelink_size_formlabel,
        uploads_description,
        uploads_type
    ',
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['tt_content']['palettes']['uploadslayout'] = [
    'label' => 'core.form.palettes:downloads_layout',
    'showitem' => '
        filelink_size,
        uploads_description,
        uploads_type
    ',
];

?>
