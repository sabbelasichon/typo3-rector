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

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Frontend\Resource\FileCollector;

/**
 * Contains FILES content object
 */
class FilesContentObject extends AbstractContentObject
{
    /**
     * Rendering the cObject FILES
     *
     * @param array $conf Array of TypoScript properties
     * @return string Output
     */
    public function render($conf = [])
    {
        if (!empty($conf['if.']) && !$this->cObj->checkIf($conf['if.'])) {
            return '';
        }
        $frontendController = $this->hasTypoScriptFrontendController()
            ? $this->getTypoScriptFrontendController()
            : null;
        // Store the original "currentFile" within a variable so it can be re-applied later-on
        $originalFileInContentObject = $this->cObj->getCurrentFile();

        $fileCollector = $this->findAndSortFiles($conf);
        $fileObjects = $fileCollector->getFiles();
        $availableFileObjectCount = count($fileObjects);

        // optionSplit applied to conf to allow different settings per file
        $splitConf = GeneralUtility::makeInstance(TypoScriptService::class)
            ->explodeConfigurationForOptionSplit($conf, $availableFileObjectCount);

        $start = (int)$this->cObj->stdWrapValue('begin', $conf, 0);
        $start = MathUtility::forceIntegerInRange($start, 0, $availableFileObjectCount);

        $limit = (int)$this->cObj->stdWrapValue('maxItems', $conf, $availableFileObjectCount);
        $end = MathUtility::forceIntegerInRange($start + $limit, $start, $availableFileObjectCount);

        if ($frontendController !== null) {
            $frontendController->register['FILES_COUNT'] = min($limit, $availableFileObjectCount);
        }
        $fileObjectCounter = 0;
        $keys = array_keys($fileObjects);

        $content = '';
        for ($i = $start; $i < $end; $i++) {
            $key = $keys[$i];
            $fileObject = $fileObjects[$key];

            if ($frontendController !== null) {
                $frontendController->register['FILE_NUM_CURRENT'] = $fileObjectCounter;
            }
            $this->cObj->setCurrentFile($fileObject);
            $content .= $this->cObj->cObjGetSingle($splitConf[$key]['renderObj'], $splitConf[$key]['renderObj.'], 'renderObj');
            $fileObjectCounter++;
        }

        // Reset current file within cObj to the original file after rendering output of FILES
        // so e.g. stdWrap is not working on the last current file applied, thus avoiding side-effects
        $this->cObj->setCurrentFile($originalFileInContentObject);

        return $this->cObj->stdWrap($content, $conf['stdWrap.'] ?? []);
    }

    /**
     * Function to check for references, collections, folders and
     * accumulates into one etc.
     *
     * @return FileCollector
     */
    protected function findAndSortFiles(array $conf)
    {
        $fileCollector = $this->getFileCollector();

        // Getting the files
        if ((isset($conf['references']) && $conf['references']) || (isset($conf['references.']) && $conf['references.'])) {
            /*
            The TypoScript could look like this:
            # all items related to the page.media field:
            references {
                table = pages
                uid.data = page:uid
                fieldName = media
            }
            # or: sys_file_references with uid 27:
            references = 27
             */
            $referencesUidList = (string)$this->cObj->stdWrapValue('references', $conf ?? []);
            $referencesUids = GeneralUtility::intExplode(',', $referencesUidList, true);
            $fileCollector->addFileReferences($referencesUids);

            if (!empty($conf['references.'])) {
                $this->addFileReferences($conf, (array)$this->cObj->data, $fileCollector);
            }
        }

        if ((isset($conf['files']) && $conf['files']) || (isset($conf['files.']) && $conf['files.'])) {
            /*
            The TypoScript could look like this:
            # with sys_file UIDs:
            files = 12,14,15# using stdWrap:
            files.field = some_field
             */
            $fileUids = GeneralUtility::intExplode(',', (string)$this->cObj->stdWrapValue('files', $conf ?? []), true);
            $fileCollector->addFiles($fileUids);
        }

        if ((isset($conf['collections']) && $conf['collections']) || (isset($conf['collections.']) && $conf['collections.'])) {
            $collectionUids = GeneralUtility::intExplode(',', (string)$this->cObj->stdWrapValue('collections', $conf ?? []), true);
            $fileCollector->addFilesFromFileCollections($collectionUids);
        }

        if ((isset($conf['folders']) && $conf['folders']) || (isset($conf['folders.']) && $conf['folders.'])) {
            $folderIdentifiers = GeneralUtility::trimExplode(',', (string)$this->cObj->stdWrapValue('folders', $conf ?? []));
            $fileCollector->addFilesFromFolders($folderIdentifiers, !empty($conf['folders.']['recursive']));
        }

        // Enable sorting for multiple fileObjects
        $sortingProperty = (string)$this->cObj->stdWrapValue('sorting', $conf ?? []);
        if ($sortingProperty !== '') {
            $sortingDirection = $this->cObj->stdWrapValue('direction', $conf['sorting.'] ?? []);
            $fileCollector->sort($sortingProperty, $sortingDirection);
        }

        return $fileCollector;
    }

    /**
     * Handles and resolves file references.
     *
     * @param array $configuration TypoScript configuration
     * @param array $element The parent element referencing to files
     */
    protected function addFileReferences(array $configuration, array $element, FileCollector $fileCollector)
    {
        // It's important that this always stays "fieldName" and not be renamed to "field" as it would otherwise collide with the stdWrap key of that name
        $referencesFieldName = $this->cObj->stdWrapValue('fieldName', $configuration['references.'] ?? []);

        // If no reference fieldName is set, there's nothing to do
        if (empty($referencesFieldName)) {
            return;
        }

        $currentId = !empty($element['uid']) ? $element['uid'] : 0;
        $tableName = $this->cObj->getCurrentTable();

        // Fetch the references of the default element
        $referencesForeignTable = (string)$this->cObj->stdWrapValue('table', $configuration['references.'], $tableName);
        $referencesForeignUid = (int)$this->cObj->stdWrapValue('uid', $configuration['references.'], $currentId);

        $pageRepository = $this->getPageRepository();
        // Fetch element if definition has been modified via TypoScript
        if (
            ($referencesForeignTable !== '' && $referencesForeignTable !== $tableName)
            || ($referencesForeignUid !== 0 && $referencesForeignUid !== $currentId)
        ) {
            $element = $pageRepository->getRawRecord($referencesForeignTable, $referencesForeignUid);

            $pageRepository->versionOL($referencesForeignTable, $element, true);
            if (is_array($element)) {
                $element = $pageRepository->getLanguageOverlay($referencesForeignTable, $element);
            }
        }

        if (is_array($element)) {
            $fileCollector->addFilesFromRelation($referencesForeignTable ?: $tableName, $referencesFieldName, $element);
        }
    }

    /**
     * @return FileCollector
     */
    protected function getFileCollector()
    {
        return GeneralUtility::makeInstance(FileCollector::class);
    }
}
