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

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Abstract container has various methods used by the container classes
 */
abstract class AbstractContainer extends AbstractNode
{
    /**
     * Merge field information configuration with default and render them.
     *
     * @return array Result array
     */
    protected function renderFieldInformation(): array
    {
        $options = $this->data;
        $fieldInformation = $this->defaultFieldInformation;
        $currentRenderType = $this->data['renderType'];
        $fieldInformationFromTca = $options['processedTca']['ctrl']['container'][$currentRenderType]['fieldInformation'] ?? [];
        ArrayUtility::mergeRecursiveWithOverrule($fieldInformation, $fieldInformationFromTca);
        $options['renderType'] = 'fieldInformation';
        $options['renderData']['fieldInformation'] = $fieldInformation;
        return $this->nodeFactory->create($options)->render();
    }

    /**
     * Merge field control configuration with default controls and render them.
     *
     * @return array Result array
     */
    protected function renderFieldControl(): array
    {
        $options = $this->data;
        $fieldControl = $this->defaultFieldControl;
        $currentRenderType = $this->data['renderType'];
        $fieldControlFromTca = $options['processedTca']['ctrl']['container'][$currentRenderType]['fieldControl'] ?? [];
        ArrayUtility::mergeRecursiveWithOverrule($fieldControl, $fieldControlFromTca);
        $options['renderType'] = 'fieldControl';
        $options['renderData']['fieldControl'] = $fieldControl;
        return $this->nodeFactory->create($options)->render();
    }

    /**
     * Merge field wizard configuration with default wizards and render them.
     *
     * @return array Result array
     */
    protected function renderFieldWizard(): array
    {
        $options = $this->data;
        $fieldWizard = $this->defaultFieldWizard;
        $currentRenderType = $this->data['renderType'];
        $fieldWizardFromTca = $options['processedTca']['ctrl']['container'][$currentRenderType]['fieldWizard'] ?? [];
        ArrayUtility::mergeRecursiveWithOverrule($fieldWizard, $fieldWizardFromTca);
        $options['renderType'] = 'fieldWizard';
        $options['renderData']['fieldWizard'] = $fieldWizard;
        return $this->nodeFactory->create($options)->render();
    }

    /**
     * A single field of TCA 'types' 'showitem' can have three semicolon separated configuration options:
     *   fieldName: Name of the field to be found in TCA 'columns' section
     *   fieldLabel: An alternative field label
     *   paletteName: Name of a palette to be found in TCA 'palettes' section that is rendered after this field
     *
     * @param string $field Semicolon separated field configuration
     * @throws \RuntimeException
     * @return array
     */
    protected function explodeSingleFieldShowItemConfiguration($field)
    {
        $fieldArray = GeneralUtility::trimExplode(';', $field);
        if (empty($fieldArray[0])) {
            throw new \RuntimeException('Field must not be empty', 1426448465);
        }
        return [
            'fieldName' => $fieldArray[0],
            'fieldLabel' => !empty($fieldArray[1]) ? $fieldArray[1] : null,
            'paletteName' => !empty($fieldArray[2]) ? $fieldArray[2] : null,
        ];
    }

    /**
     * Render tabs with label and content. Used by TabsContainer and FlexFormTabsContainer.
     * Re-uses the template Tabs.html which is also used by ModuleTemplate.php.
     *
     * @param array $menuItems Tab elements, each element is an array with "label" and "content"
     * @param string $domId DOM id attribute, will be appended with an iteration number per tab.
     * @param int $defaultTabIndex
     * @return string
     */
    protected function renderTabMenu(array $menuItems, $domId, $defaultTabIndex = 1)
    {
        // @todo: It's unfortunate we're using Typo3Fluid TemplateView directly here. We can't
        //        inject BackendViewFactory here since __construct() is polluted by NodeInterface.
        //        Remove __construct() from NodeInterface to have DI, then use BackendViewFactory here.
        $view = GeneralUtility::makeInstance(TemplateView::class);
        $templatePaths = $view->getRenderingContext()->getTemplatePaths();
        $templatePaths->setTemplateRootPaths([GeneralUtility::getFileAbsFileName('EXT:backend/Resources/Private/Templates')]);
        $templatePaths->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:backend/Resources/Private/Partials')]);
        $view->assignMultiple([
            'id' => $domId,
            'items' => $menuItems,
            'defaultTabIndex' => $defaultTabIndex,
            'wrapContent' => false,
            'storeLastActiveTab' => true,
        ]);
        return $view->render('Form/Tabs');
    }
}
