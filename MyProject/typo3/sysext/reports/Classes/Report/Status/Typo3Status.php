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

namespace TYPO3\CMS\Reports\Report\Status;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Reports\Status;
use TYPO3\CMS\Reports\Status as ReportStatus;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * Performs basic checks about the TYPO3 install
 */
class Typo3Status implements StatusProviderInterface
{
    /**
     * Returns the status for this report
     *
     * @return Status[] List of statuses
     */
    public function getStatus(): array
    {
        $statuses = [
            'registeredXclass' => $this->getRegisteredXclassStatus(),
        ];
        return $statuses;
    }

    public function getLabel(): string
    {
        return 'typo3';
    }

    /**
     * List any Xclasses registered in the system
     *
     * @return \TYPO3\CMS\Reports\Status
     */
    protected function getRegisteredXclassStatus()
    {
        $message = '';
        $value = $this->getLanguageService()->sL('LLL:EXT:reports/Resources/Private/Language/locallang_reports.xlf:status_none');
        $severity = ContextualFeedbackSeverity::OK;

        $xclassFoundArray = [];
        if (array_key_exists('Objects', $GLOBALS['TYPO3_CONF_VARS']['SYS'])) {
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'] as $originalClass => $override) {
                if (array_key_exists('className', $override)) {
                    $xclassFoundArray[$originalClass] = $override['className'];
                }
            }
        }
        if (!empty($xclassFoundArray)) {
            $value = $this->getLanguageService()->sL('LLL:EXT:reports/Resources/Private/Language/locallang_reports.xlf:status_xclassUsageFound');
            $message = $this->getLanguageService()->sL('LLL:EXT:reports/Resources/Private/Language/locallang_reports.xlf:status_xclassUsageFound_message') . '<br />';
            $message .= '<ol>';
            foreach ($xclassFoundArray as $originalClass => $xClassName) {
                $messageDetail = sprintf(
                    (string)($this->getLanguageService()->sL('LLL:EXT:reports/Resources/Private/Language/locallang_reports.xlf:status_xclassUsageFound_message_detail')),
                    '<code>' . htmlspecialchars((string)$originalClass) . '</code>',
                    '<code>' . htmlspecialchars((string)$xClassName) . '</code>'
                );
                $message .= '<li>' . $messageDetail . '</li>';
            }
            $message .= '</ol>';
            $severity = ContextualFeedbackSeverity::NOTICE;
        }

        return GeneralUtility::makeInstance(
            ReportStatus::class,
            $this->getLanguageService()->sL('LLL:EXT:reports/Resources/Private/Language/locallang_reports.xlf:status_xclassUsage'),
            $value,
            $message,
            $severity
        );
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
