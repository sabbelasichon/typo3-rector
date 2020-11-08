<?php

use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$localImageProcessor = GeneralUtility::makeInstance(LocalImageProcessor::class);
$localImageProcessor->getTemporaryImageWithText('foo', 'bar', 'baz', 'foo');

?>
-----
<?php

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;
use TYPO3\CMS\Core\Resource\Processing\LocalImageProcessor;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$localImageProcessor = GeneralUtility::makeInstance(LocalImageProcessor::class);
GeneralUtility::makeInstance(GraphicalFunctions::class)->getTemporaryImageWithText('foo', 'bar', 'baz', 'foo');
