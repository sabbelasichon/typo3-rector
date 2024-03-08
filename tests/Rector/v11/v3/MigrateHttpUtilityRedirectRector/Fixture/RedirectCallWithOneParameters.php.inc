<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\MigrateHttpUtilityRedirectRector\Fixture;

use TYPO3\CMS\Core\Utility\HttpUtility;

HttpUtility::redirect('https://example.com');
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v11\v3\MigrateHttpUtilityRedirectRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ResponseFactoryInterface;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\HttpUtility;

$response = GeneralUtility::makeInstance(ResponseFactoryInterface::class)->createResponse(HttpUtility::HTTP_STATUS_303)->withAddedHeader('location', 'https://example.com');
throw new PropagateResponseException($response);
?>
