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

namespace TYPO3\CMS\Workspaces\Dependency;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Versioning\VersionState;

/**
 * Processor having generic callback methods for element entities
 */
class ElementEntityProcessor
{
    /**
     * @var int
     */
    protected $workspace;

    /**
     * @var DataHandler|null
     */
    protected $dataHandler;

    /**
     * Sets the current workspace.
     *
     * @param int $workspace
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = (int)$workspace;
    }

    /**
     * Gets the current workspace.
     *
     * @return int
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }

    /**
     * @return DataHandler
     */
    public function getDataHandler()
    {
        if (!isset($this->dataHandler)) {
            $this->dataHandler = GeneralUtility::makeInstance(DataHandler::class);
        }
        return $this->dataHandler;
    }

    /**
     * Transforms dependent elements to use the liveId as array key.
     *
     * @param array|ElementEntity[] $elements
     * @return array
     */
    public function transformDependentElementsToUseLiveId(array $elements)
    {
        $transformedElements = [];
        /** @var ElementEntity $element */
        foreach ($elements as $element) {
            $elementName = ElementEntity::getIdentifier($element->getTable(), $element->getDataValue('liveId'));
            $transformedElements[$elementName] = $element;
        }
        return $transformedElements;
    }

    /**
     * Callback to determine whether a new child reference shall be considered in the dependency resolver utility.
     *
     * @param string $eventName
     * @return string|null Skip response (if required)
     */
    public function createNewDependentElementChildReferenceCallback(array $callerArguments, array $targetArgument, ElementEntity $caller, $eventName)
    {
        // skip children in case ancestor is invalid
        if ($caller->isInvalid()) {
            return ElementEntity::RESPONSE_Skip;
        }
        $fieldConfiguration = BackendUtility::getTcaFieldConfiguration($caller->getTable(), $callerArguments['field']);
        $inlineFieldType = $this->getDataHandler()->getRelationFieldType($fieldConfiguration);
        if (!$fieldConfiguration || ($fieldConfiguration['type'] !== 'flex' && $inlineFieldType !== 'field' && $inlineFieldType !== 'list')) {
            return ElementEntity::RESPONSE_Skip;
        }
        return null;
    }

    /**
     * Callback to determine whether a new parent reference shall be considered in the dependency resolver utility.
     *
     * @param string $eventName
     * @return string|null Skip response (if required)
     */
    public function createNewDependentElementParentReferenceCallback(array $callerArguments, array $targetArgument, ElementEntity $caller, $eventName)
    {
        $fieldConfiguration = BackendUtility::getTcaFieldConfiguration($callerArguments['table'], $callerArguments['field']);
        $inlineFieldType = $this->getDataHandler()->getRelationFieldType($fieldConfiguration);
        if (!$fieldConfiguration || ($fieldConfiguration['type'] !== 'flex' && $inlineFieldType !== 'field' && $inlineFieldType !== 'list')) {
            return ElementEntity::RESPONSE_Skip;
        }
        return null;
    }

    /**
     * Callback to determine whether a new child reference shall be considered in the dependency resolver utility.
     * Only elements that are a delete placeholder are considered.
     *
     * @param string $eventName
     * @return string|null Skip response (if required)
     */
    public function createClearDependentElementChildReferenceCallback(array $callerArguments, array $targetArgument, ElementEntity $caller, $eventName)
    {
        $response = $this->createNewDependentElementChildReferenceCallback($callerArguments, $targetArgument, $caller, $eventName);
        if (empty($response)) {
            $record = BackendUtility::getRecord($callerArguments['table'], $callerArguments['id']);
            if (!VersionState::cast($record['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER)) {
                $response = ElementEntity::RESPONSE_Skip;
            }
        }
        return $response;
    }

    /**
     * Callback to determine whether a new parent reference shall be considered in the dependency resolver utility.
     * Only elements that are a delete placeholder are considered.
     *
     * @param string $eventName
     * @return string|null Skip response (if required)
     */
    public function createClearDependentElementParentReferenceCallback(array $callerArguments, array $targetArgument, ElementEntity $caller, $eventName)
    {
        $response = $this->createNewDependentElementParentReferenceCallback($callerArguments, $targetArgument, $caller, $eventName);
        if (empty($response)) {
            $record = BackendUtility::getRecord($callerArguments['table'], $callerArguments['id']);
            if (!VersionState::cast($record['t3ver_state'] ?? 0)->equals(VersionState::DELETE_PLACEHOLDER)) {
                $response = ElementEntity::RESPONSE_Skip;
            }
        }
        return $response;
    }

    /**
     * Callback to add additional data to new elements created in the dependency resolver utility.
     *
     * @throws \RuntimeException
     * @param string $eventName
     */
    public function createNewDependentElementCallback(array $callerArguments, array $targetArgument, ElementEntity $caller, $eventName)
    {
        if (!BackendUtility::isTableWorkspaceEnabled($caller->getTable())) {
            $caller->setInvalid(true);
            return;
        }

        $versionRecord = $caller->getRecord();
        // If version record does not exist, it probably has been deleted (cleared from workspace), this means,
        // that the reference index still has an old reference pointer, which is "fine" for deleted parents
        if (empty($versionRecord)) {
            throw new \RuntimeException(
                'Element "' . $caller::getIdentifier($caller->getTable(), $caller->getId()) . '" does not exist',
                1393960943
            );
        }

        $deleteFieldName = $GLOBALS['TCA'][$caller->getTable()]['ctrl']['delete'] ?? null;
        // If version is on live workspace, but an "offline" ID is set, mark the record as invalid.
        // This happens if a change has been discarded (clearWSID) - it will be removed from the command map.
        if (
            (int)$versionRecord['t3ver_oid'] > 0 && (
                (int)$versionRecord['t3ver_wsid'] === 0 // behavior prior to v10.1 (backward compatibility)
                || !empty($deleteFieldName) && (int)$versionRecord['t3ver_wsid'] === $this->getWorkspace()
                    && (int)$versionRecord[$deleteFieldName] > 0 // behavior since v10.1
            )
        ) {
            $caller->setDataValue('liveId', $caller->getId());
            $caller->setInvalid(true);
            return;
        }
        if ($caller->hasDataValue('liveId') === false) {
            // Set the original uid from the version record
            if (!empty($versionRecord['t3ver_oid']) && (int)$versionRecord['t3ver_wsid'] === $this->getWorkspace()) {
                $caller->setDataValue('liveId', $versionRecord['t3ver_oid']);
            } elseif ((int)$versionRecord['t3ver_wsid'] === 0 || (int)$versionRecord['t3ver_oid'] === 0) {
                // The current version record is actually a live record or an accordant placeholder for live
                $caller->setDataValue('liveId', $caller->getId());
                $versionRecord = BackendUtility::getWorkspaceVersionOfRecord(
                    $this->getWorkspace(),
                    $caller->getTable(),
                    $caller->getId(),
                    'uid,t3ver_state'
                );
                // Set version uid to caller, most likely it's a delete placeholder
                // for a child record that is not recognized in the reference index
                if (!empty($versionRecord['uid'])) {
                    $caller->setId($versionRecord['uid']);
                } else {
                    // If no version could be determined, mark record as invalid
                    // (thus, it will be removed from the command map)
                    $caller->setInvalid(true);
                }
            } else {
                // In case of an unexpected record state, mark the record as invalid
                $caller->setInvalid(true);
            }
        }
    }
}
