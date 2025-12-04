<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['sys_reaction']['palettes']['createRecord'] = [
    'label' => 'LLL:EXT:reactions/Resources/Private/Language/locallang_db.xlf:palette.additional',
    'showitem' => 'table_name, --linebreak--, storage_pid, impersonate_user, --linebreak--, fields',
];

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePaletteLabelsRector\Palette;

$GLOBALS['TCA']['sys_reaction']['palettes']['createRecord'] = [
    'label' => 'reactions.db:palette.additional',
    'showitem' => 'table_name, --linebreak--, storage_pid, impersonate_user, --linebreak--, fields',
];

?>
