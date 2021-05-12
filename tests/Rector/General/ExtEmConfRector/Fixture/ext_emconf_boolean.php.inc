<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\ExtEmConfRector\Fixture;

$EM_CONF = [];
$_EXTKEY = 'foo';

$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'clearcacheonload' => 1,
];

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\General\ExtEmConfRector\Fixture;

$EM_CONF = [];
$_EXTKEY = 'foo';

$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'clearCacheOnLoad' => true,
];

?>
