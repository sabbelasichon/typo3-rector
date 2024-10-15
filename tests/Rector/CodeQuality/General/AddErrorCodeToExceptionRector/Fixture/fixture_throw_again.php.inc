<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

try {
    throw new \Exception('Hello');
} catch (\Exception $e) {
    throw $e;
} finally {
    throw new \Exception('Bye');
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

try {
    throw new \Exception('Hello', 1729021897);
} catch (\Exception $e) {
    throw $e;
} finally {
    throw new \Exception('Bye', 1729021897);
}
?>
