<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\Fixture;

defined('TYPO3_MODE') or die();

if (TYPO3_REQUESTTYPE_FE) {

}

if (TYPO3_REQUESTTYPE_BE) {

}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v0\SubstituteConstantsModeAndRequestTypeRector\Fixture;

defined('TYPO3') or die();

if (TYPO3_REQUESTTYPE_FE) {

}

if (TYPO3_REQUESTTYPE_BE) {

}

?>
