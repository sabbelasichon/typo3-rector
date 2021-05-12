<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v5\RemoveSupportForTransForeignTableRector\Fixture;

return [
    'ctrl' => [
        'transForeignTable' => 'l10n_parent',
        'transOrigPointerTable' => 'l10n_parent',
    ],
    'columns' => [],
];

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v5\RemoveSupportForTransForeignTableRector\Fixture;

return [
    'ctrl' => [],
    'columns' => [],
];

?>
