<?php

defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'seo',
    'Configuration/TypoScript/XmlSitemap',
    'XML Sitemap'
);
