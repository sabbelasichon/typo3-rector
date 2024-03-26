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
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Render a given list of field of a TCA table.
 *
 * This is an entry container called from FormEngine to handle a
 * list of specific fields. Access rights are checked here and globalOption array
 * is prepared for further processing of single fields by PaletteAndSingleContainer.
 */
class ListOfFieldsContainer extends AbstractContainer
{
    /**
     * Entry method
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $table = $this->data['tableName'];
        $fieldListToRender = $this->data['fieldListToRender'];
        $recordTypeValue = $this->data['recordTypeValue'];

        $fieldListToRender = array_unique(GeneralUtility::trimExplode(',', $fieldListToRender, true));

        $fieldsByShowitem = $this->data['processedTca']['types'][$recordTypeValue]['showitem'];
        $fieldsByShowitem = GeneralUtility::trimExplode(',', $fieldsByShowitem, true);

        $finalFieldsList = [];
        foreach ($fieldListToRender as $fieldName) {
            foreach ($fieldsByShowitem as $fieldByShowitem) {
                $fieldByShowitemArray = $this->explodeSingleFieldShowItemConfiguration($fieldByShowitem);
                if ($fieldByShowitemArray['fieldName'] === $fieldName) {
                    $finalFieldsList[] = implode(';', $fieldByShowitemArray);
                    break;
                }
                if ($fieldByShowitemArray['fieldName'] === '--palette--'
                    && isset($this->data['processedTca']['palettes'][$fieldByShowitemArray['paletteName']]['showitem'])
                    && is_string($this->data['processedTca']['palettes'][$fieldByShowitemArray['paletteName']]['showitem'])
                ) {
                    $paletteName = $fieldByShowitemArray['paletteName'];
                    $paletteFields = GeneralUtility::trimExplode(',', $this->data['processedTca']['palettes'][$paletteName]['showitem'], true);
                    foreach ($paletteFields as $paletteField) {
                        $paletteFieldArray = $this->explodeSingleFieldShowItemConfiguration($paletteField);
                        if ($paletteFieldArray['fieldName'] === $fieldName) {
                            $finalFieldsList[] = implode(';', $paletteFieldArray);
                            break;
                        }
                    }
                }
            }
        }

        $options = $this->data;
        $options['fieldsArray'] = $finalFieldsList;
        $options['renderType'] = 'paletteAndSingleContainer';
        return $this->nodeFactory->create($options)->render();
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
