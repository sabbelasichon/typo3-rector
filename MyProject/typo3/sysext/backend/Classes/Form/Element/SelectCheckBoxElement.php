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

namespace TYPO3\CMS\Backend\Form\Element;

use TYPO3\CMS\Backend\Form\Utility\FormEngineUtility;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;

/**
 * Creates a widget with check box elements.
 *
 * This is rendered for config type=select, renderType=selectCheckBox
 */
class SelectCheckBoxElement extends AbstractFormElement
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
     * Default field wizards enabled for this element.
     *
     * @var array
     */
    protected $defaultFieldWizard = [
        'localizationStateSelector' => [
            'renderType' => 'localizationStateSelector',
        ],
        'otherLanguageContent' => [
            'renderType' => 'otherLanguageContent',
            'after' => [
                'localizationStateSelector',
            ],
        ],
        'defaultLanguageDifferences' => [
            'renderType' => 'defaultLanguageDifferences',
            'after' => [
                'otherLanguageContent',
            ],
        ],
    ];

    /**
     * Render check boxes
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $resultArray = $this->initializeResultArray();
        // @deprecated since v12, will be removed with v13 when all elements handle label/legend on their own
        $resultArray['labelHasBeenHandled'] = true;

        // Field configuration from TCA:
        $parameterArray = $this->data['parameterArray'];
        $config = $parameterArray['fieldConf']['config'];
        $readOnly = (bool)($config['readOnly'] ?? false);

        $selectItems = $config['items'] ?? [];
        if (empty($selectItems)) {
            // Early return in case the field does not contain any items
            return $resultArray;
        }

        // Get item value as array and make unique, which is fine because there can be no duplicates anyway.
        $itemArray = array_flip($parameterArray['itemFormElValue']);

        // Initialize variables and traverse the items
        $groups = [];
        $currentGroup = 0;
        $counter = 0;
        foreach ($selectItems as $item) {
            // Non-selectable element:
            if ($item['value'] === '--div--') {
                $selIcon = '';
                if (isset($item['icon']) && $item['icon'] !== 'empty-empty') {
                    $selIcon = FormEngineUtility::getIconHtml($item['icon']);
                }
                $currentGroup++;
                $groups[$currentGroup]['header'] = [
                    'icon' => $selIcon,
                    'title' => $item['label'],
                ];
            } else {
                // Check if some help text is available
                // Help text is expected to be an associative array
                // with two key, "title" and "description"
                // For the sake of backwards compatibility, we test if the help text
                // is a string and use it as a description (this could happen if items
                // are modified with an itemProcFunc)
                $help = '';
                if (!empty($item['description'])) {
                    if (is_array($item['description'])) {
                        $helpArray = $item['description'];
                    } else {
                        $helpArray['description'] = $item['description'];
                    }
                    $help = $this->wrapInHelp($helpArray);
                }

                // Check if current item is selected. If found, unset the key in the $itemArray.
                $checked = isset($itemArray[$item['value']]);
                if ($checked) {
                    unset($itemArray[$item['value']]);
                }

                // Build item array
                $groups[$currentGroup]['items'][] = [
                    'id' => StringUtility::getUniqueId('select_checkbox_row_'),
                    'name' => $parameterArray['itemFormElName'] . '[' . $counter . ']',
                    'value' => $item['value'],
                    'checked' => $checked,
                    'icon' => FormEngineUtility::getIconHtml(!empty($item['icon']) ? $item['icon'] : 'empty-empty'),
                    'title' => $item['label'],
                    'help' => $help,
                ];
                $counter++;
            }
        }

        $fieldInformationResult = $this->renderFieldInformation();
        $fieldInformationHtml = $fieldInformationResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldInformationResult, false);

        $fieldWizardResult = $this->renderFieldWizard();
        $fieldWizardHtml = $fieldWizardResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldWizardResult, false);

        $html[] = '<div class="formengine-field-item t3js-formengine-field-item" data-formengine-validation-rules="' . htmlspecialchars($this->getValidationDataAsJsonString($config)) . '">';
        $html[] = $fieldInformationHtml;
        $html[] =   '<div class="form-wizards-wrap">';
        $html[] =       '<div class="form-wizards-element">';

        if (!$readOnly) {
            // Add an empty hidden field which will send a blank value if all items are unselected.
            $html[] = '<input type="hidden" class="select-checkbox" name="' . htmlspecialchars($parameterArray['itemFormElName']) . '" value="">';
        }

        // Building the checkboxes
        foreach ($groups as $groupKey => $group) {
            $groupId = htmlspecialchars($parameterArray['itemFormElID']) . '-group-' . $groupKey;
            $groupIdCollapsible = $groupId . '-collapse';
            $hasGroupHeader = is_array($group['header'] ?? false);

            $html[] = '<div id="' . $groupId . '" class="panel panel-default">';
            if ($hasGroupHeader) {
                $html[] = '<div class="panel-heading">';
                $html[] =    '<a data-bs-toggle="collapse" href="#' . $groupIdCollapsible . '" aria-expanded="false" aria-controls="' . $groupIdCollapsible . '">';
                $html[] =        $group['header']['icon'];
                $html[] =        htmlspecialchars($group['header']['title']);
                $html[] =    '</a>';
                $html[] = '</div>';
            }
            if (!empty($group['items']) && is_array($group['items'])) {
                $tableRows = [];

                // Render rows
                foreach ($group['items'] as $item) {
                    $inputElementAttrs = [
                        'type' => 'checkbox',
                        'class' => 't3js-checkbox',
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'value' => $item['value'],
                    ];

                    if ($item['checked']) {
                        $inputElementAttrs['checked'] = 'checked';
                    }

                    if ($readOnly) {
                        // Disable item if the element is readonly
                        $inputElementAttrs['disabled'] = 'disabled';
                    } else {
                        // Add fieldChange attributes if element is not readOnly
                        $inputElementAttrs = array_merge(
                            $inputElementAttrs,
                            $this->getOnFieldChangeAttrs('click', $parameterArray['fieldChangeFunc'] ?? [])
                        );
                    }

                    $tableRows[] = '<tr>';
                    $tableRows[] =    '<td class="col-checkbox">';
                    $tableRows[] =        '<input ' . GeneralUtility::implodeAttributes($inputElementAttrs, true, true) . '>';
                    $tableRows[] =    '</td>';
                    $tableRows[] =    '<td class="col-title">';
                    $tableRows[] =        '<label class="label-block nowrap-disabled" for="' . $item['id'] . '">';
                    $tableRows[] =            '<span class="inline-icon">' . $item['icon'] . '</span>';
                    $tableRows[] =            htmlspecialchars($this->appendValueToLabelInDebugMode($item['title'], $item['value']), ENT_COMPAT, 'UTF-8', false);
                    $tableRows[] =        '</label>';
                    $tableRows[] =    '</td>';
                    $tableRows[] =    '<td class="text-end">' . $item['help'] . '</td>';
                    $tableRows[] = '</tr>';
                }

                if ($hasGroupHeader) {
                    $expandAll = ($config['appearance']['expandAll'] ?? false) ? 'show' : '';
                    $html[] = '<div id="' . $groupIdCollapsible . '" class="panel-collapse collapse ' . $expandAll . '" role="tabpanel">';
                }

                $html[] =    '<div class="table-fit">';
                $html[] =        '<table class="table table-transparent table-hover">';
                if (!$readOnly) {
                    $checkboxId = StringUtility::getUniqueId($groupId);
                    $title = htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.toggleall'));

                    // Add table header with actions, in case the element is not readOnly
                    $html[] =            '<thead>';
                    $html[] =                '<tr>';
                    $html[] =                    '<th class="col-checkbox">';
                    $html[] =                       '<input type="checkbox" id="' . $checkboxId . '" class="t3js-toggle-checkboxes" title="' . $title . '" />';
                    $html[] =                    '</th>';
                    $html[] =                    '<th class="col-title"><label for="' . $checkboxId . '">' . $title . '</label></th>';
                    $html[] =                    '<th class="text-end">';
                    $html[] =                       '<button type="button" class="btn btn-default btn-sm t3js-revert-selection">';
                    $html[] =                           $this->iconFactory->getIcon('actions-edit-undo', Icon::SIZE_SMALL)->render() . ' ';
                    $html[] =                           htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.revertSelection'));
                    $html[] =                       '</button>';
                    $html[] =                    '</th>';
                    $html[] =                '</tr>';
                    $html[] =            '</thead>';

                    // Add JavaScript module. This is only needed, in case the element
                    // is not readOnly, since otherwise no checkbox changes take place.
                    $resultArray['javaScriptModules'][] = JavaScriptModuleInstruction::create(
                        '@typo3/backend/form-engine/element/select-check-box-element.js'
                    )->instance($checkboxId);
                }
                $html[] =            '<tbody>' . implode(LF, $tableRows) . '</tbody>';
                $html[] =        '</table>';
                $html[] =    '</div>';
                if ($hasGroupHeader) {
                    $html[] = '</div>';
                }
            }
            $html[] = '</div>';
        }

        $html[] =       '</div>';
        if (!$readOnly && !empty($fieldWizardHtml)) {
            $html[] =   '<div class="form-wizards-items-bottom">';
            $html[] =       $fieldWizardHtml;
            $html[] =   '</div>';
        }
        $html[] =   '</div>';
        $html[] = '</div>';

        $resultArray['html'] = $this->wrapWithFieldsetAndLegend(implode(LF, $html));
        return $resultArray;
    }

    /**
     * A function that creates an icon with a help text. If a user clicks on
     * the icon, the help text will show up as tooltip
     *
     * @param array $overloadHelpText Array with text to overload help text
     * @return string the HTML code ready to render
     */
    protected function wrapInHelp(array $overloadHelpText = []): string
    {
        // If there's a help text or some overload information, proceed with preparing an output
        if (empty($overloadHelpText)) {
            return '';
        }
        $iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $text = $iconFactory->getIcon('actions-system-help-open', Icon::SIZE_SMALL)->render();
        $abbrClassAdd = ' help-teaser-icon';
        $text = '<abbr class="help-teaser' . $abbrClassAdd . '">' . $text . '</abbr>';
        $wrappedText = '<span class="help-link" data-bs-content="<p></p>"';
        // The overload array may provide a title and a description
        // If either one is defined, add them to the "data" attributes
        if (isset($overloadHelpText['title'])) {
            $wrappedText .= ' data-title="' . htmlspecialchars($overloadHelpText['title']) . '"';
        }
        if (isset($overloadHelpText['description'])) {
            $wrappedText .= ' data-description="' . htmlspecialchars($overloadHelpText['description']) . '"';
        }
        $wrappedText .= '>' . $text . '</span>';
        return $wrappedText;
    }
}
