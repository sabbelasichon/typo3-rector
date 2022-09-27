<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector\Fixture;

use \TYPO3\CMS\Core\Messaging\FlashMessage;

$flashMessage = new FlashMessage('This is a message');
$severityAsInt = $flashMessage->getSeverity();
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\AbstractMessageGetSeverityRector\Fixture;

use \TYPO3\CMS\Core\Messaging\FlashMessage;

$flashMessage = new FlashMessage('This is a message');
$severityAsInt = $flashMessage->getSeverity()->value;
?>