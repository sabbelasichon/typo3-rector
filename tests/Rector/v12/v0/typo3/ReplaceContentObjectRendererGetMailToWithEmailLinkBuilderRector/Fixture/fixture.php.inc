<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$mailAddress = 'foo@bar.com';
$linkText = 'baz';

/** @var ContentObjectRenderer $cObj */
$cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$result = $cObj->getMailTo($mailAddress, $linkText);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\ReplaceContentObjectRendererGetMailToWithEmailLinkBuilderRector\Fixture;

use TYPO3\CMS\Frontend\Typolink\EmailLinkBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$mailAddress = 'foo@bar.com';
$linkText = 'baz';

/** @var ContentObjectRenderer $cObj */
$cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$result = GeneralUtility::makeInstance(EmailLinkBuilder::class, $cObj, $cObj->getTypoScriptFrontendController())->processEmailLink((string) $mailAddress, (string) $linkText);

?>
