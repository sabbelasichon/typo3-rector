<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFrameworkRector\Fixture;

use TYPO3\CMS\Core\Tests\FunctionalTestCase;

class MyFunctionalTest extends FunctionalTestCase
{

}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v6\CoreTestsToTestingFrameworkRector\Fixture;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MyFunctionalTest extends FunctionalTestCase
{

}

?>
