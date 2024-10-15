<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

if ('a' === 'b') {
    throw new \RuntimeException('my message');
}

if ('b' === 'c') {
    throw new \RuntimeException();
}

throw new \RuntimeException('do not touch this', 564);
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

if ('a' === 'b') {
    throw new \RuntimeException('my message', 1729021897);
}

if ('b' === 'c') {
    throw new \RuntimeException();
}

throw new \RuntimeException('do not touch this', 564);
?>
