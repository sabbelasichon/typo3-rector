<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Fixture;

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
    }

    public function mergeConfigurationCapabilities($capabilities)
    {
    }

    public function hasCapability($capability)
    {
    }

    public function isCaseSensitiveFileSystem()
    {
    }

    public function sanitizeFileName($fileName, $charset = '')
    {
    }

    public function hashIdentifier($identifier)
    {
    }

    public function getRootLevelFolder()
    {
    }

    public function getDefaultFolder()
    {
    }

    public function getParentFolderIdentifierOfIdentifier($fileIdentifier)
    {
    }

    public function getPublicUrl($identifier)
    {
    }

    public function createFolder($newFolderName, $parentFolderIdentifier = '', $recursive = false)
    {
    }

    public function renameFolder($folderIdentifier, $newName)
    {
    }

    public function deleteFolder($folderIdentifier, $deleteRecursively = false)
    {
    }

    public function fileExists($fileIdentifier)
    {
    }

    public function folderExists($folderIdentifier)
    {
    }

    public function isFolderEmpty($folderIdentifier)
    {
    }

    public function addFile($localFilePath, $targetFolderIdentifier, $newFileName = '', $removeOriginal = true)
    {
    }

    public function createFile($fileName, $parentFolderIdentifier)
    {
    }

    public function copyFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $fileName)
    {
    }

    public function renameFile($fileIdentifier, $newName)
    {
    }

    public function replaceFile($fileIdentifier, $localFilePath)
    {
    }

    public function deleteFile($fileIdentifier)
    {
    }

    public function hash($fileIdentifier, $hashAlgorithm)
    {
    }

    public function moveFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $newFileName)
    {
    }

    public function moveFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
    }

    public function copyFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
    }

    public function getFileContents($fileIdentifier)
    {
    }

    public function setFileContents($fileIdentifier, $contents)
    {
    }

    public function fileExistsInFolder($fileName, $folderIdentifier)
    {
    }

    public function folderExistsInFolder($folderName, $folderIdentifier)
    {
    }

    public function getFileForLocalProcessing($fileIdentifier, $writable = true)
    {
    }

    public function getPermissions($identifier)
    {
    }

    public function dumpFileContents($identifier)
    {
    }

    public function isWithin($folderIdentifier, $identifier)
    {
    }

    public function getFileInfoByIdentifier($fileIdentifier, array $propertiesToExtract = [])
    {
    }

    public function getFolderInfoByIdentifier($folderIdentifier)
    {
    }

    public function getFileInFolder($fileName, $folderIdentifier)
    {
    }

    public function getFilesInFolder($folderIdentifier, $start = 0, $numberOfItems = 0, $recursive = false, array $filenameFilterCallbacks = [], $sort = '', $sortRev = false)
    {
    }

    public function getFolderInFolder($folderName, $folderIdentifier)
    {
    }

    public function getFoldersInFolder($folderIdentifier, $start = 0, $numberOfItems = 0, $recursive = false, array $folderNameFilterCallbacks = [], $sort = '', $sortRev = false)
    {
    }

    public function countFilesInFolder($folderIdentifier, $recursive = false, array $filenameFilterCallbacks = [])
    {
    }

    public function countFoldersInFolder($folderIdentifier, $recursive = false, array $folderNameFilterCallbacks = [])
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateObsoleteCharsetInSanitizeFileNameRector\Fixture;

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
    }

    public function mergeConfigurationCapabilities($capabilities)
    {
    }

    public function hasCapability($capability)
    {
    }

    public function isCaseSensitiveFileSystem()
    {
    }

    public function sanitizeFileName($fileName)
    {
    }

    public function hashIdentifier($identifier)
    {
    }

    public function getRootLevelFolder()
    {
    }

    public function getDefaultFolder()
    {
    }

    public function getParentFolderIdentifierOfIdentifier($fileIdentifier)
    {
    }

    public function getPublicUrl($identifier)
    {
    }

    public function createFolder($newFolderName, $parentFolderIdentifier = '', $recursive = false)
    {
    }

    public function renameFolder($folderIdentifier, $newName)
    {
    }

    public function deleteFolder($folderIdentifier, $deleteRecursively = false)
    {
    }

    public function fileExists($fileIdentifier)
    {
    }

    public function folderExists($folderIdentifier)
    {
    }

    public function isFolderEmpty($folderIdentifier)
    {
    }

    public function addFile($localFilePath, $targetFolderIdentifier, $newFileName = '', $removeOriginal = true)
    {
    }

    public function createFile($fileName, $parentFolderIdentifier)
    {
    }

    public function copyFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $fileName)
    {
    }

    public function renameFile($fileIdentifier, $newName)
    {
    }

    public function replaceFile($fileIdentifier, $localFilePath)
    {
    }

    public function deleteFile($fileIdentifier)
    {
    }

    public function hash($fileIdentifier, $hashAlgorithm)
    {
    }

    public function moveFileWithinStorage($fileIdentifier, $targetFolderIdentifier, $newFileName)
    {
    }

    public function moveFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
    }

    public function copyFolderWithinStorage($sourceFolderIdentifier, $targetFolderIdentifier, $newFolderName)
    {
    }

    public function getFileContents($fileIdentifier)
    {
    }

    public function setFileContents($fileIdentifier, $contents)
    {
    }

    public function fileExistsInFolder($fileName, $folderIdentifier)
    {
    }

    public function folderExistsInFolder($folderName, $folderIdentifier)
    {
    }

    public function getFileForLocalProcessing($fileIdentifier, $writable = true)
    {
    }

    public function getPermissions($identifier)
    {
    }

    public function dumpFileContents($identifier)
    {
    }

    public function isWithin($folderIdentifier, $identifier)
    {
    }

    public function getFileInfoByIdentifier($fileIdentifier, array $propertiesToExtract = [])
    {
    }

    public function getFolderInfoByIdentifier($folderIdentifier)
    {
    }

    public function getFileInFolder($fileName, $folderIdentifier)
    {
    }

    public function getFilesInFolder($folderIdentifier, $start = 0, $numberOfItems = 0, $recursive = false, array $filenameFilterCallbacks = [], $sort = '', $sortRev = false)
    {
    }

    public function getFolderInFolder($folderName, $folderIdentifier)
    {
    }

    public function getFoldersInFolder($folderIdentifier, $start = 0, $numberOfItems = 0, $recursive = false, array $folderNameFilterCallbacks = [], $sort = '', $sortRev = false)
    {
    }

    public function countFilesInFolder($folderIdentifier, $recursive = false, array $filenameFilterCallbacks = [])
    {
    }

    public function countFoldersInFolder($folderIdentifier, $recursive = false, array $folderNameFilterCallbacks = [])
    {
    }
}
?>
