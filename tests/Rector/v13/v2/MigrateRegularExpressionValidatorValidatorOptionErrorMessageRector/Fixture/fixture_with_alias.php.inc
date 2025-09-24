<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    #[Extbase\Validate([
        'validator' => 'RegularExpression',
        'options' => [
            'regularExpression' => '/^simple[0-9]expression$/',
            'errorMessage' => 'Error message or LLL schema string',
        ],
    ])]
    protected string $myProperty = '';
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v2\MigrateRegularExpressionValidatorValidatorOptionErrorMessageRector\Fixture;

use TYPO3\CMS\Extbase\Annotation as Extbase;

class MyClass
{
    #[Extbase\Validate([
        'validator' => 'RegularExpression',
        'options' => [
            'regularExpression' => '/^simple[0-9]expression$/',
            'message' => 'Error message or LLL schema string',
        ],
    ])]
    protected string $myProperty = '';
}

?>
