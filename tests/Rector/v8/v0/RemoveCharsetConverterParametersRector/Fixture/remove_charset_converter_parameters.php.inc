<?php

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class RemoveCharsetConverterParameters
{
    public function method(): void
    {
        $charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
        $charsetConvert->entities_to_utf8('string', false);
        $charsetConvert->utf8_to_numberarray('string', false, false);
    }
}
?>
-----
<?php

use TYPO3\CMS\Core\Charset\CharsetConverter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class RemoveCharsetConverterParameters
{
    public function method(): void
    {
        $charsetConvert = GeneralUtility::makeInstance(CharsetConverter::class);
        $charsetConvert->entities_to_utf8('string');
        $charsetConvert->utf8_to_numberarray('string');
    }
}
?>
