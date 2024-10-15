<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

abstract class CustomException extends \Exception
{
    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddErrorCodeToExceptionRector\Fixture;

abstract class CustomException extends \Exception
{
    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this), 1729021897);
        }
        parent::__construct($message, $code);
    }
}
?>
