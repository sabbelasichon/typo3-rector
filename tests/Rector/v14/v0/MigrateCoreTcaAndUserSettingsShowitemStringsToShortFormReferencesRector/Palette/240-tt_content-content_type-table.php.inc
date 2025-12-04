<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['tt_content']['palettes']['tablelayout'] = [
    'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.table_layout',
    'showitem' => 'cols, table_class, table_header_position, table_tfoot',
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['tt_content']['palettes']['tablelayout'] = [
    'label' => 'core.form.palettes:table_layout',
    'showitem' => 'cols, table_class, table_header_position, table_tfoot',
];

?>
