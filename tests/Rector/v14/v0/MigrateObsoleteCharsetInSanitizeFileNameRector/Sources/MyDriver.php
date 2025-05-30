<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Sources;

use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

class MyDriver implements DriverInterface
{
    public function processConfiguration()
    {
    }

    public function setStorageUid($storageUid)
    {
    }

    public function initialize()
    {
    }

    public function getCapabilities()
    {
        return 0;
    }

    public function mergeConfigurationCapabilities($capabilities)
    {
        return 0;
    }

    public function hasCapability($capability)
    {
        return false;
    }

    public function isCaseSensitiveFileSystem()
    {
        return false;
    }

    public function sanitizeFileName($fileName)
    {
        return '';
    }

    public function hashIdentifier($identifier)
    {
        return '';
    }

    public function getRootLevelFolder()
    {
        return '';
    }

    public function getDefaultFolder()
    {
        return '';
    }

    public function getParentFolderIdentifierOfIdentifier($fileIdentifier)
    {
        return '';
    }

    public function getPublicUrl($identifier)
    {
        return '';
    }

    public function createFolder($newFolderName, $parentFolderIdentifier = '', $recursive = false)
    {
        return '';
    }

    /**
     * @return array<mixed>
     */
    public function renameFolder($folderIdentifier, $newName)
    {
        return [];
    }

    public function deleteFolder($folderIdentifier, $deleteRecursively = false)
    {
        return false;
    }

    public function fileExists($fileIdentifier)
    {
        return false;
    }

    public function folderExists($folderIdentifier)
    {
        return false;
    }

    public function isFolderEmpty($folderIdentifier)
    {
        return false;
    }

    public function addFile($localFilePath, $targetFolderIdentifier, $newFileName = '', $removeOriginal = true)
    {
        return '';
    }

    public function createFile($fileName, $parentFolderIdentifier)
    {
        return '';
    }

    public function copyFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $fileName)
    {
        return '';
    }

    public function renameFile($fileIdentifier, $newName)
    {
        return '';
    }

    public function replaceFile($fileIdentifier, $localFilePath)
    {
        return false;
    }

    public function deleteFile($fileIdentifier)
    {
        return false;
    }

    public function hash($fileIdentifier, $hashAlgorithm)
    {
        return '';
    }

    public function moveFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $newFileName)
    {
        return '';
    }

    /**
     * @return array<mixed>
     */
    public function moveFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
        return [];
    }

    public function copyFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
        return false;
    }

    public function getFileContents($fileIdentifier)
    {
        return '';
    }

    public function setFileContents($fileIdentifier, $contents)
    {
        return 0;
    }

    public function fileExistsInFolder($fileName, $folderIdentifier)
    {
        return false;
    }

    public function folderExistsInFolder($folderName, $folderIdentifier)
    {
        return false;
    }

    public function getFileForLocalProcessing($fileIdentifier, $writable = true)
    {
        return '';
    }

    /**
     * @return array<mixed>
     */
    public function getPermissions($identifier)
    {
        return [];
    }

    /**
     * @return string
     */
    public function dumpFileContents($identifier)
    {
        return '';
    }

    public function isWithin($folderIdentifier, $identifier)
    {
        return false;
    }

    /**
     * @param array<string> $propertiesToExtract
     * @return array<mixed>
     */
    public function getFileInfoByIdentifier($fileIdentifier, array $propertiesToExtract = [])
    {
        return [];
    }

    /**
     * @return array<mixed>
     */
    public function getFolderInfoByIdentifier($folderIdentifier)
    {
        return [];
    }

    public function getFileInFolder($fileName, $folderIdentifier)
    {
        return '';
    }

    /**
     * @param array<mixed> $filenameFilterCallbacks
     * @return array<mixed>
     */
    public function getFilesInFolder(
        $folderIdentifier,
        $start = 0,
        $numberOfItems = 0,
        $recursive = false,
        array $filenameFilterCallbacks = [],
        $sort = '',
        $sortRev = false
    ) {
        return [];
    }

    public function getFolderInFolder($folderName, $folderIdentifier)
    {
        return '';
    }

    /**
     * @param array<mixed> $folderNameFilterCallbacks
     */
    public function getFoldersInFolder(
        $folderIdentifier,
        $start = 0,
        $numberOfItems = 0,
        $recursive = false,
        array $folderNameFilterCallbacks = [],
        $sort = '',
        $sortRev = false
    ) {
        return [];
    }

    /**
     * @param array<mixed> $filenameFilterCallbacks
     */
    public function countFilesInFolder($folderIdentifier, $recursive = false, array $filenameFilterCallbacks = [])
    {
        return 0;
    }

    /**
     * @param array<mixed> $folderNameFilterCallbacks
     */
    public function countFoldersInFolder($folderIdentifier, $recursive = false, array $folderNameFilterCallbacks = [])
    {
        return 0;
    }
}
