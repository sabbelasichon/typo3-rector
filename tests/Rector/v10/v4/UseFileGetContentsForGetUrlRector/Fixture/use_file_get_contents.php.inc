<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\UseFileGetContentsForGetUrlRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;

GeneralUtility::getUrl('some.csv');
$externalUrl = 'https://domain.com';
GeneralUtility::getUrl($externalUrl);

GeneralUtility::getUrl($externalUrl, 1);

class MyClass
{
    public function foo($url): string
    {
        return GeneralUtility::getUrl($url);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\UseFileGetContentsForGetUrlRector\Fixture;

use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

@file_get_contents('some.csv');
$externalUrl = 'https://domain.com';
GeneralUtility::makeInstance(RequestFactory::class)->request($externalUrl)->getBody()->getContents();

GeneralUtility::getUrl($externalUrl, 1);

class MyClass
{
    public function foo($url): string
    {
        return GeneralUtility::getUrl($url);
    }
}

?>
