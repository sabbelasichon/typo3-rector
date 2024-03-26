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

namespace TYPO3\CMS\Extensionmanager\Report;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Domain\Model\Extension;
use TYPO3\CMS\Extensionmanager\Remote\RemoteRegistry;
use TYPO3\CMS\Extensionmanager\Utility\ListUtility;
use TYPO3\CMS\Reports\Status;
use TYPO3\CMS\Reports\StatusProviderInterface;

/**
 * Extension status reports
 * @internal This class is a specific EXT:reports implementation and is not part of the Public TYPO3 API.
 */
class ExtensionStatus implements StatusProviderInterface
{
    protected string $ok = '';
    protected string $error = '';

    protected LanguageService $languageService;

    public function __construct(
        protected readonly RemoteRegistry $remoteRegistry,
        protected readonly ListUtility $listUtility,
        protected readonly LanguageServiceFactory $languageServiceFactory,
    ) {
        $this->languageService = $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER'] ?? null);
    }

    /**
     * Determines extension manager status
     *
     * @return array<string, Status|array> List of statuses
     */
    public function getStatus(): array
    {
        $status = [];

        if (!Environment::isComposerMode()) {
            $status['mainRepositoryStatus'] = $this->getMainRepositoryStatus();
        }

        $extensionStatus = $this->getSecurityStatusOfExtensions();
        $status['extensionsSecurityStatusInstalled'] = $extensionStatus->loaded ?? [];
        $status['extensionsSecurityStatusNotInstalled'] = $extensionStatus->existing ?? [];
        $status['extensionsOutdatedStatusInstalled'] = $extensionStatus->loadedoutdated ?? [];
        $status['extensionsOutdatedStatusNotInstalled'] = $extensionStatus->existingoutdated ?? [];

        return $status;
    }

    public function getLabel(): string
    {
        return 'Extension Manager';
    }

    /**
     * Check main repository status: existence, has extensions, last update younger than 7 days
     *
     * @return Status
     */
    protected function getMainRepositoryStatus()
    {
        if (!$this->remoteRegistry->hasDefaultRemote()) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.notFound.value');
            $message = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.notFound.message');
            $severity = ContextualFeedbackSeverity::ERROR;
        } elseif ($this->remoteRegistry->getDefaultRemote()->needsUpdate()) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.notUpToDate.value');
            $message = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.notUpToDate.message');
            $severity = ContextualFeedbackSeverity::NOTICE;
        } else {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.upToDate.value');
            $message = '';
            $severity = ContextualFeedbackSeverity::OK;
        }

        $status = GeneralUtility::makeInstance(
            Status::class,
            $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.mainRepository.title'),
            $value,
            $message,
            $severity
        );

        return $status;
    }

    /**
     * Get security status of loaded and installed extensions
     *
     * @return \stdClass with properties 'loaded' and 'existing' containing a TYPO3\CMS\Reports\Report\Status\Status object
     */
    protected function getSecurityStatusOfExtensions()
    {
        $extensionInformation = $this->listUtility->getAvailableAndInstalledExtensionsWithAdditionalInformation();
        $loadedInsecure = [];
        $existingInsecure = [];
        $loadedOutdated = [];
        $existingOutdated = [];
        foreach ($extensionInformation as $extensionKey => $information) {
            if (
                array_key_exists('terObject', $information)
                && $information['terObject'] instanceof Extension
            ) {
                $terObject = $information['terObject'];
                $insecureStatus = $terObject->getReviewState();
                if ($insecureStatus === -1) {
                    if (
                        array_key_exists('installed', $information)
                        && $information['installed'] === true
                    ) {
                        $loadedInsecure[] = [
                            'extensionKey' => $extensionKey,
                            'version' => $terObject->getVersion(),
                        ];
                    } else {
                        $existingInsecure[] = [
                            'extensionKey' => $extensionKey,
                            'version' => $terObject->getVersion(),
                        ];
                    }
                } elseif ($insecureStatus === -2) {
                    if (
                        array_key_exists('installed', $information)
                        && $information['installed'] === true
                    ) {
                        $loadedOutdated[] = [
                            'extensionKey' => $extensionKey,
                            'version' => $terObject->getVersion(),
                        ];
                    } else {
                        $existingOutdated[] = [
                            'extensionKey' => $extensionKey,
                            'version' => $terObject->getVersion(),
                        ];
                    }
                }
            }
        }

        $result = new \stdClass();

        if (empty($loadedInsecure)) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedExtensions.noInsecureExtensionLoaded.value');
            $message = '';
            $severity = ContextualFeedbackSeverity::OK;
        } else {
            $value = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedExtensions.insecureExtensionLoaded.value'),
                count($loadedInsecure)
            );
            $extensionList = [];
            foreach ($loadedInsecure as $insecureExtension) {
                $extensionList[] = sprintf(
                    $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedExtensions.insecureExtensionLoaded.message.extension'),
                    $insecureExtension['extensionKey'],
                    $insecureExtension['version']
                );
            }
            $message = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedExtensions.insecureExtensionLoaded.message'),
                implode('', $extensionList)
            );
            $severity = ContextualFeedbackSeverity::ERROR;
        }
        $result->loaded = GeneralUtility::makeInstance(
            Status::class,
            $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedExtensions.title'),
            $value,
            $message,
            $severity
        );

        if (empty($existingInsecure)) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingExtensions.noInsecureExtensionExists.value');
            $message = '';
            $severity = ContextualFeedbackSeverity::OK;
        } else {
            $value = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingExtensions.insecureExtensionExists.value'),
                count($existingInsecure)
            );
            $extensionList = [];
            foreach ($existingInsecure as $insecureExtension) {
                $extensionList[] = sprintf(
                    $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingExtensions.insecureExtensionExists.message.extension'),
                    $insecureExtension['extensionKey'],
                    $insecureExtension['version']
                );
            }
            $message = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingExtensions.insecureExtensionExists.message'),
                implode('', $extensionList)
            );
            $severity = ContextualFeedbackSeverity::WARNING;
        }
        $result->existing = GeneralUtility::makeInstance(
            Status::class,
            $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingExtensions.title'),
            $value,
            $message,
            $severity
        );

        if (empty($loadedOutdated)) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedOutdatedExtensions.noOutdatedExtensionLoaded.value');
            $message = '';
            $severity = ContextualFeedbackSeverity::OK;
        } else {
            $value = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedOutdatedExtensions.outdatedExtensionLoaded.value'),
                count($loadedOutdated)
            );
            $extensionList = [];
            foreach ($loadedOutdated as $outdatedExtension) {
                $extensionList[] = sprintf(
                    $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedOutdatedExtensions.outdatedExtensionLoaded.message.extension'),
                    $outdatedExtension['extensionKey'],
                    $outdatedExtension['version']
                );
            }
            $message = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedOutdatedExtensions.outdatedExtensionLoaded.message'),
                implode('', $extensionList)
            );
            $severity = ContextualFeedbackSeverity::WARNING;
        }
        $result->loadedoutdated = GeneralUtility::makeInstance(
            Status::class,
            $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.loadedOutdatedExtensions.title'),
            $value,
            $message,
            $severity
        );

        if (empty($existingOutdated)) {
            $value = $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingOutdatedExtensions.noOutdatedExtensionExists.value');
            $message = '';
            $severity = ContextualFeedbackSeverity::OK;
        } else {
            $value = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingOutdatedExtensions.outdatedExtensionExists.value'),
                count($existingOutdated)
            );
            $extensionList = [];
            foreach ($existingOutdated as $outdatedExtension) {
                $extensionList[] = sprintf(
                    $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingOutdatedExtensions.outdatedExtensionExists.message.extension'),
                    $outdatedExtension['extensionKey'],
                    $outdatedExtension['version']
                );
            }
            $message = sprintf(
                $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingOutdatedExtensions.outdatedExtensionExists.message'),
                implode('', $extensionList)
            );
            $severity = ContextualFeedbackSeverity::WARNING;
        }
        $result->existingoutdated = GeneralUtility::makeInstance(
            Status::class,
            $this->languageService->sL('LLL:EXT:extensionmanager/Resources/Private/Language/locallang.xlf:report.status.existingOutdatedExtensions.title'),
            $value,
            $message,
            $severity
        );

        return $result;
    }
}
