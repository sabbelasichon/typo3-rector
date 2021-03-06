<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerModule(
    'TYPO3.CMS.Workspaces',
    'web',
    'workspaces',
    'before:info',
    [
        // An array holding the controller-action-combinations that are accessible
        'Review' => 'index,fullIndex,singleIndex',
        'Preview' => 'index,newPage'
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:workspaces/Resources/Public/Icons/module-workspaces.svg',
        'navigationComponentId' => '',
        'labels' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_mod.xlf',
    ]
);

?>
