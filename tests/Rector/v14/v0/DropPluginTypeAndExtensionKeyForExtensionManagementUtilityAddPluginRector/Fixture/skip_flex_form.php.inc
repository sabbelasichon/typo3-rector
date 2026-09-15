<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DropPluginTypeAndExtensionKeyForExtensionManagementUtilityAddPluginRector\Fixture;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group']);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'FILE:EXT:my_extension/Configuration/FlexForms/Plugin.xml');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], '<T3DataStructure><ROOT></ROOT></T3DataStructure>');
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], $flexForm);
ExtensionManagementUtility::addPlugin(['title', 'signature', 'icon', 'group'], 'FILE:EXT:' . $extensionKey . '/Configuration/FlexForms/Plugin.xml');
ExtensionManagementUtility::addTcaSelectItem('tt_content', 'CType', ['label' => 'title', 'value' => 'signature']);
