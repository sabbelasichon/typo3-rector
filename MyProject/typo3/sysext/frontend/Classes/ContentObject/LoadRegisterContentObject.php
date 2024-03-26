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

/**
 * Contains LOAD_REGISTER class object.
 */
class LoadRegisterContentObject extends AbstractContentObject
{
    /**
     * Rendering the cObject, LOAD_REGISTER
     * NOTICE: This cObject does NOT return any content since it just sets internal data based on the TypoScript properties.
     *
     * @param array $conf Array of TypoScript properties
     * @return string Empty string (the cObject only sets internal data!)
     */
    public function render($conf = [])
    {
        $frontendController = $this->getTypoScriptFrontendController();
        $frontendController->registerStack[] = $frontendController->register;
        if (is_array($conf)) {
            $isExecuted = [];
            foreach ($conf as $theKey => $theValue) {
                $register = rtrim($theKey, '.');
                if (!isset($isExecuted[$register]) || !$isExecuted[$register]) {
                    $registerProperties = $register . '.';
                    if (isset($conf[$register]) && isset($conf[$registerProperties])) {
                        $theValue = $this->cObj->stdWrap($conf[$register], $conf[$registerProperties]);
                    } elseif (isset($conf[$registerProperties])) {
                        $theValue = $this->cObj->stdWrap('', $conf[$registerProperties]);
                    }
                    $frontendController->register[$register] = $theValue;
                    $isExecuted[$register] = true;
                }
            }
        }
        return '';
    }
}
