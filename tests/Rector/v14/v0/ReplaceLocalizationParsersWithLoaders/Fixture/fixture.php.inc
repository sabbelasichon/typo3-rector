<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ReplaceLocalizationParsersWithLoaders\Fixture;

use TYPO3\CMS\Core\Localization\Parser\XliffParser;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['lang']['parser']['xlf'] = XliffParser::class;

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\ReplaceLocalizationParsersWithLoaders\Fixture;

use TYPO3\CMS\Core\Localization\Loader\XliffLoader;
use TYPO3\CMS\Core\Localization\Parser\XliffParser;

$GLOBALS['TYPO3_CONF_VARS']['LANG']['loader']['xlf'] = XliffLoader::class;

?>
