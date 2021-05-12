<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class MyFunctionalTest extends FunctionalTestCase
{

}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class MyFunctionalTest extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase
{

}

?>
