<?php

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$resourceFactory = ResourceFactory::getInstance();

?>
-----
<?php

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);

?>
