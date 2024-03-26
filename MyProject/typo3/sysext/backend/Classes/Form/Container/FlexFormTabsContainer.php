<?php

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

namespace TYPO3\CMS\Backend\Form\Container;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;

/**
 * Handle flex forms that have tabs (multiple "sheets").
 *
 * This container is called by FlexFormEntryContainer. It resolves each
 * sheet and hands rendering of single sheet content over to FlexFormElementContainer.
 */
class FlexFormTabsContainer extends AbstractContainer
{
    /**
     * Default field information enabled for this element.
     *
     * @var array
     */
    protected $defaultFieldInformation = [
        'tcaDescription' => [
            'renderType' => 'tcaDescription',
        ],
    ];

    /**
     * Entry method
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $languageService = $this->getLanguageService();

        $parameterArray = $this->data['parameterArray'];
        $flexFormDataStructureArray = $this->data['flexFormDataStructureArray'];
        $flexFormRowData = $this->data['flexFormRowData'];

        $resultArray = $this->initializeResultArray();
        $resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create('@typo3/backend/tabs.js');

        $domIdPrefix = 'DTM-' . md5($this->data['parameterArray']['itemFormElName']);
        $tabCounter = 0;
        $tabElements = [];
        foreach ($flexFormDataStructureArray['sheets'] as $sheetName => $sheetDataStructure) {
            $flexFormRowSheetDataSubPart = $flexFormRowData['data'][$sheetName]['lDEF'] ?? [];

            if (!is_array($sheetDataStructure['ROOT']['el'])) {
                $resultArray['html'] .= LF . 'No Data Structure ERROR: No [\'ROOT\'][\'el\'] found for sheet "' . $sheetName . '".';
                continue;
            }

            $tabCounter++;

            $options = $this->data;
            $options['flexFormDataStructureArray'] = $sheetDataStructure['ROOT']['el'];
            $options['flexFormRowData'] = $flexFormRowSheetDataSubPart;
            $options['flexFormSheetName'] = $sheetName;
            $options['flexFormFormPrefix'] = '[data][' . $sheetName . '][lDEF]';
            $options['parameterArray'] = $parameterArray;
            // Merge elements of this tab into a single list again and hand over to
            // palette and single field container to render this group
            $options['tabAndInlineStack'][] = [
                'tab',
                $domIdPrefix . '-' . $tabCounter,
            ];
            $options['renderType'] = 'flexFormElementContainer';
            $childReturn = $this->nodeFactory->create($options)->render();

            if ($childReturn['html'] !== '') {
                $tabElements[] = [
                    'label' => !empty(trim($sheetDataStructure['ROOT']['sheetTitle'] ?? '')) ? $languageService->sL(trim($sheetDataStructure['ROOT']['sheetTitle'])) : $sheetName,
                    'content' => $childReturn['html'],
                    'description' => trim($sheetDataStructure['ROOT']['sheetDescription'] ?? '') ? $languageService->sL(trim($sheetDataStructure['ROOT']['sheetDescription'])) : '',
                    'linkTitle' => trim($sheetDataStructure['ROOT']['sheetShortDescr'] ?? '') ? $languageService->sL(trim($sheetDataStructure['ROOT']['sheetShortDescr'])) : '',
                ];
            }
            $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $childReturn, false);
        }

        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray['html'] = '<div>' . $fieldInformationResult['html'] . '</div>';
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldInformationResult, false);

        $resultArray['html'] .= $this->renderTabMenu($tabElements, $domIdPrefix);
        return $resultArray;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
