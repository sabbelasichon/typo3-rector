<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceSeveritiesFlashMessageAndReportsRector\Fixture;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Reports\Status;

$flashMessage = new FlashMessage();
$flashMessage->setSeverity(FlashMessage::NOTICE);
$flashMessage->setSeverity(FlashMessage::ERROR);
$flashMessage->setSeverity(FlashMessage::INFO);
$flashMessage->setSeverity(FlashMessage::OK);
$flashMessage->setSeverity(FlashMessage::WARNING);

$statusNotice = new Status(Status::NOTICE);
$statusInfo = new Status(Status::INFO);
$statusWarning = new Status(Status::WARNING);
$statusOk = new Status(Status::OK);
$statusError = new Status(Status::ERROR);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceSeveritiesFlashMessageAndReportsRector\Fixture;

use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Reports\Status;

$flashMessage = new FlashMessage();
$flashMessage->setSeverity(ContextualFeedbackSeverity::NOTICE);
$flashMessage->setSeverity(ContextualFeedbackSeverity::ERROR);
$flashMessage->setSeverity(ContextualFeedbackSeverity::INFO);
$flashMessage->setSeverity(ContextualFeedbackSeverity::OK);
$flashMessage->setSeverity(ContextualFeedbackSeverity::WARNING);

$statusNotice = new Status(ContextualFeedbackSeverity::NOTICE);
$statusInfo = new Status(ContextualFeedbackSeverity::INFO);
$statusWarning = new Status(ContextualFeedbackSeverity::WARNING);
$statusOk = new Status(ContextualFeedbackSeverity::OK);
$statusError = new Status(ContextualFeedbackSeverity::ERROR);

?>
