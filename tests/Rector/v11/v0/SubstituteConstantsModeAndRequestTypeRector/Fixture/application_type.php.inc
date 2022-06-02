<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\Fixture;

class MyController
{
    public function fooAction()
    {
        if (TYPO3_REQUESTTYPE_BE) {
            // Do something
        }

        if (TYPO3_REQUESTTYPE_FE) {
            // Do something
        }

        if (TYPO3_MODE === 'BE') {
            // Do something
        }

        if (TYPO3_MODE === 'FE') {
            // Do something
        }

        if ('BE' === TYPO3_MODE) {
            // Do something
        }

        if ('FOO' === TYPO3_MODE) {
            // Do something
        }

        if ('FE' === TYPO3_MODE) {
            // Do something
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\Fixture;

use TYPO3\CMS\Core\Http\ApplicationType;
class MyController
{
    public function fooAction()
    {
        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
            // Do something
        }

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            // Do something
        }

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
            // Do something
        }

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            // Do something
        }

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
            // Do something
        }

        if ('FOO' === TYPO3_MODE) {
            // Do something
        }

        if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            // Do something
        }
    }
}

?>
