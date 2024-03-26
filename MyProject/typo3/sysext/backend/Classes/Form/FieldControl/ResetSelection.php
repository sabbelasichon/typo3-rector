<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace TYPO3\CMS\Backend\Form\FieldControl;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * "Reset selection to previous selected items" icon,
 * typically used by type=select with renderType=selectSingleBox
 */
class ResetSelection extends AbstractNode
{
    /**
     * Add button control
     *
     * @return array As defined by FieldControl class
     */
    public function render()
    {
        $parameterArray = $this->data['parameterArray'];
        $selectItems = $parameterArray['fieldConf']['config']['items'];
        if (($parameterArray['fieldConf']['config']['readOnly'] ?? false) || empty($selectItems)) {
            // Early return if the field is readOnly or no items exist
            return [];
        }
        $itemName = $parameterArray['itemFormElName'];
        $itemArray = array_flip($parameterArray['itemFormElValue']);
        $initiallySelectedIndices = [];
        foreach ($selectItems as $i => $item) {
            $value = $item['value'];
            // Selected or not by default
            if (isset($itemArray[$value])) {
                $initiallySelectedIndices[] = $i;
            }
        }

        $id = StringUtility::getUniqueId('t3js-formengine-fieldcontrol-');

        return [
            'iconIdentifier' => 'actions-edit-undo',
            'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.revertSelection',
            'linkAttributes' => [
                'id' => $id,
                'data-item-name' => $itemName,
                'data-selected-indices' => json_encode($initiallySelectedIndices),
            ],
            'javaScriptModules' => [
                JavaScriptModuleInstruction::create('@typo3/backend/form-engine/field-control/reset-selection.js')->instance('#' . $id),
            ],
        ];
    }
}
