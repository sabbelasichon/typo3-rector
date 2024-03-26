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

/**
 * Generation of TCEform elements of where the type is unknown
 */
class UnknownElement extends AbstractFormElement
{
    /**
     * Handler for unknown types.
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render()
    {
        $resultArray = $this->initializeResultArray();
        // @deprecated since v12, will be removed with v13 when all elements handle label/legend on their own
        $resultArray['labelHasBeenHandled'] = true;
        $type = $this->data['parameterArray']['fieldConf']['config']['type'];
        $renderType = $this->data['renderType'];
        $resultArray['html'] = $this->wrapWithFieldsetAndLegend(
            '<div class="alert alert-warning">Unknown type: <code>' . $type . '</code>' . ($renderType ? ', render type: <code>' . $renderType . '</code>' : '') . '</div>'
        );
        return $resultArray;
    }
}
