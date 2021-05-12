<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;

class MyViewHelperTest extends ViewHelperBaseTestcase
{

}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\TestCase\ViewHelperBaseTestcase;

class MyViewHelperTest extends \TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase
{

}

?>
