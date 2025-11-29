<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UsageOfValidationAttributesAtMethodLevelRector\Fixture;

use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    #[IgnoreValidation(argumentName: 'something')]
    public function barAction(string $something)
    {
    }

    #[Validate(validator: 'NotEmpty', param: 'anythingNotEmpty')]
    public function bazAction(string $anythingNotEmpty)
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\UsageOfValidationAttributesAtMethodLevelRector\Fixture;

use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyController extends ActionController
{
    public function barAction(
        #[IgnoreValidation]
        string $something
    )
    {
    }

    public function bazAction(
        #[Validate(validator: 'NotEmpty')]
        string $anythingNotEmpty
    )
    {
    }
}

?>
