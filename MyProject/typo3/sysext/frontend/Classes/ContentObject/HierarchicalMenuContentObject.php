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

namespace TYPO3\CMS\Frontend\ContentObject;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\Menu\Exception\NoSuchMenuTypeException;
use TYPO3\CMS\Frontend\ContentObject\Menu\MenuContentObjectFactory;

/**
 * Contains HMENU class object.
 */
class HierarchicalMenuContentObject extends AbstractContentObject
{
    /**
     * Rendering the cObject, HMENU
     *
     * @param array $conf Array of TypoScript properties
     * @return string Output
     */
    public function render($conf = [])
    {
        if (!empty($conf['if.']) && !$this->cObj->checkIf($conf['if.'])) {
            return '';
        }

        $theValue = '';
        $menuType = $conf[1] ?? '';
        try {
            $frontendController = $this->getTypoScriptFrontendController();
            $menuObjectFactory = GeneralUtility::makeInstance(MenuContentObjectFactory::class);
            $menu = $menuObjectFactory->getMenuObjectByType($menuType);
            if (isset($frontendController->register['count_HMENU'])) {
                $frontendController->register['count_HMENU']++;
            } else {
                $frontendController->register['count_HMENU'] = 1;
            }
            $frontendController->register['count_HMENU_MENUOBJ'] = 0;
            $frontendController->register['count_MENUOBJ'] = 0;
            $menu->parent_cObj = $this->getContentObjectRenderer();
            // @deprecated since v12: Hand over null as first argument in v13 when TemplateService is removed.
            $menu->start($frontendController->tmpl, $this->getPageRepository(), '', $conf, 1, '', $this->request);
            $menu->makeMenu();
            $theValue .= $menu->writeMenu();
        } catch (NoSuchMenuTypeException $e) {
        }
        $wrap = $this->cObj->stdWrapValue('wrap', $conf ?? []);
        if ($wrap) {
            $theValue = $this->cObj->wrap($theValue, $wrap);
        }
        if (isset($conf['stdWrap.'])) {
            $theValue = $this->cObj->stdWrap($theValue, $conf['stdWrap.']);
        }
        return $theValue;
    }
}
