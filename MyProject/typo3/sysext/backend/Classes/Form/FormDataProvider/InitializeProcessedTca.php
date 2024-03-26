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

namespace TYPO3\CMS\Backend\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;

/**
 * Initialize processed TCA from vanilla TCA
 */
class InitializeProcessedTca implements FormDataProviderInterface
{
    /**
     * Add processed TCA as copy from vanilla TCA and sanitize some details
     *
     * @return array
     * @throws \UnexpectedValueException
     */
    public function addData(array $result)
    {
        if (empty($result['processedTca'])) {
            if (
                !isset($GLOBALS['TCA'][$result['tableName']])
                || !is_array($GLOBALS['TCA'][$result['tableName']])
            ) {
                throw new \UnexpectedValueException(
                    'TCA for table ' . $result['tableName'] . ' not found',
                    1437914223
                );
            }

            $result['processedTca'] = $GLOBALS['TCA'][$result['tableName']];
        }

        if (!is_array($result['processedTca']['columns'])) {
            throw new \UnexpectedValueException(
                'No columns definition in TCA table ' . $result['tableName'],
                1438594406
            );
        }

        return $result;
    }
}
