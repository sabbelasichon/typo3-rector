<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesDriverInterfaceRector\Fixture;

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

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesDriverInterfaceRector\Fixture;

use TYPO3\CMS\Core\Resource\Capabilities;
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;

class MyDriver implements DriverInterface
{
    public function processConfiguration(): void
    {
    }

    public function setStorageUid(int $storageUid): void
    {
    }

    public function initialize(): void
    {
    }

    public function getCapabilities(): Capabilities
    {
    }

    public function mergeConfigurationCapabilities(Capabilities $capabilities): Capabilities
    {
    }

    public function hasCapability(int $capability): bool
    {
    }

    public function isCaseSensitiveFileSystem(): bool
    {
    }

    public function sanitizeFileName(string $fileName, string $charset = ''): string
    {

    }

    public function hashIdentifier(string $identifier): string
    {

    }

    public function getRootLevelFolder(): string
    {

    }

    public function getDefaultFolder(): string
    {

    }

    public function getParentFolderIdentifierOfIdentifier(string $fileIdentifier): ?string
    {

    }

    public function getPublicUrl(string $identifier): ?string
    {

    }

    public function createFolder(string $newFolderName, string $parentFolderIdentifier = '', bool $recursive = false): string
    {

    }

    public function renameFolder(string $folderIdentifier, string $newName): array
    {

    }

    public function deleteFolder(string $folderIdentifier, bool $deleteRecursively = false): bool
    {

    }

    public function fileExists(string $fileIdentifier): bool
    {

    }

    public function folderExists(string $folderIdentifier): bool
    {

    }

    public function isFolderEmpty(string $folderIdentifier): bool
    {

    }

    public function addFile(string $localFilePath, string $targetFolderIdentifier, string $newFileName = '', bool $removeOriginal = true): string
    {

    }

    public function createFile(string $fileName, string $parentFolderIdentifier): string
    {

    }

    public function copyFileWithinStorage(string $fileIdentifier, string $targetFolderIdentifier, string $fileName): string
    {

    }

    public function renameFile(string $fileIdentifier, string $newName): string
    {

    }

    public function replaceFile(string $fileIdentifier, string $localFilePath): bool
    {

    }

    public function deleteFile(string $fileIdentifier): bool
    {

    }

    public function hash(string $fileIdentifier, string $hashAlgorithm): string
    {

    }

    public function moveFileWithinStorage(string $fileIdentifier, string $targetFolderIdentifier, string $newFileName): string
    {

    }

    public function moveFolderWithinStorage(string $sourceFolderIdentifier, string $targetFolderIdentifier, string $newFolderName): array
    {

    }

    public function copyFolderWithinStorage(string $sourceFolderIdentifier, string $targetFolderIdentifier, string $newFolderName): bool
    {

    }

    public function getFileContents(string $fileIdentifier): string
    {

    }

    public function setFileContents(string $fileIdentifier, string $contents): int
    {

    }

    public function fileExistsInFolder(string $fileName, string $folderIdentifier): bool
    {

    }

    public function folderExistsInFolder(string $folderName, string $folderIdentifier): bool
    {

    }

    public function getFileForLocalProcessing(string $fileIdentifier, bool $writable = true): string
    {

    }

    public function getPermissions(string $identifier): array
    {

    }

    public function dumpFileContents(string $identifier): void
    {

    }

    public function isWithin(string $folderIdentifier, string $identifier): bool
    {

    }

    public function getFileInfoByIdentifier(string $fileIdentifier, array $propertiesToExtract = []): array
    {

    }

    public function getFolderInfoByIdentifier(string $folderIdentifier): array
    {

    }

    public function getFileInFolder(string $fileName, string $folderIdentifier): string
    {

    }

    public function getFilesInFolder(string $folderIdentifier, int $start = 0, int $numberOfItems = 0, bool $recursive = false, array $filenameFilterCallbacks = [], string $sort = '', bool $sortRev = false): array
    {

    }

    public function getFolderInFolder(string $folderName, string $folderIdentifier): string
    {

    }

    public function getFoldersInFolder(string $folderIdentifier, int $start = 0, int $numberOfItems = 0, bool $recursive = false, array $folderNameFilterCallbacks = [], string $sort = '', bool $sortRev = false): array
    {

    }

    public function countFilesInFolder(string $folderIdentifier, bool $recursive = false, array $filenameFilterCallbacks = []): int
    {

    }

    public function countFoldersInFolder(string $folderIdentifier, bool $recursive = false, array $folderNameFilterCallbacks = []): int
    {

    }
}

?>
