<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\FixturePhp8;

class CustomException
{
    public function returnMatch()
    {
        return match (true) {
            default => throw new \InvalidArgumentException('Invalid'),
        };
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\FixturePhp8;

class CustomException
{
    public function returnMatch()
    {
        return match (true) {
            default => throw new \InvalidArgumentException('Invalid', 1729021897),
        };
    }
}
?>
