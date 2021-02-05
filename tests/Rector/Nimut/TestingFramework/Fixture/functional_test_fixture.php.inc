<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use Nimut\TestingFramework\TestCase\FunctionalTestCase;

class MyFunctionalTest extends FunctionalTestCase
{

}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Nimut\TestingFramework\Fixture;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MyFunctionalTest extends FunctionalTestCase
{

}

?>
