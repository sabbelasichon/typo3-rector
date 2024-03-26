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

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * Entry container to a flex form element. This container is created by
 * SingleFieldContainer if a type='flex' field is rendered.
 *
 * It either forks a FlexFormTabsContainer or a FlexFormNoTabsContainer.
 */
class FlexFormEntryContainer extends AbstractContainer
{
    /**
     * Entry method
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $flexFormDataStructureIdentifier = $this->data['parameterArray']['fieldConf']['config']['dataStructureIdentifier'];
        $flexFormDataStructureArray = $this->data['parameterArray']['fieldConf']['config']['ds'];

        $options = $this->data;
        $options['flexFormDataStructureIdentifier'] = $flexFormDataStructureIdentifier;
        $options['flexFormDataStructureArray'] = $flexFormDataStructureArray;
        $options['flexFormRowData'] = $this->data['parameterArray']['itemFormElValue'];
        $options['renderType'] = 'flexFormNoTabsContainer';

        // Enable tabs if there is more than one sheet
        if (count($flexFormDataStructureArray['sheets']) > 1) {
            $options['renderType'] = 'flexFormTabsContainer';
        }

        $resultArray = $this->nodeFactory->create($options)->render();
        // @deprecated since v12, will be removed with v13 when all elements handle label/legend on their own
        $resultArray['labelHasBeenHandled'] = true;
        $legend = htmlspecialchars($this->data['parameterArray']['fieldConf']['label']);
        if ($this->getBackendUserAuthentication()->shallDisplayDebugInformation()) {
            $legend .= ' <code>[' . htmlspecialchars($this->data['fieldName']) . ']</code>';
        }
        $resultArray['html'] = '<fieldset><legend class="form-legend">' . $legend . '</legend>' . $resultArray['html'] . '</fieldset>';
        return $resultArray;
    }

    protected function getBackendUserAuthentication(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}
