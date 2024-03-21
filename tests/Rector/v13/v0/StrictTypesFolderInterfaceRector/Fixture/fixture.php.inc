<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesFolderInterfaceRector\Fixture;

use TYPO3\CMS\Core\Resource\Driver\DriverInterface;
use TYPO3\CMS\Core\Resource\FolderInterface;
use TYPO3\CMS\Core\Resource\ResourceStorage;

class MyFolder implements FolderInterface
{
    public function getSubfolders()
    {
    }

    public function getSubfolder($name)
    {
    }

    public function hasFolder($name)
    {
    }

    public function hasFile($name)
    {
    }

    public function getFile(string $fileName)
    {
    }

    public function rename($newName)
    {
    }

    public function delete()
    {
    }

    public function getModificationTime()
    {
    }

    public function getCreationTime()
    {
    }

    public function getIdentifier(): string
    {
    }

    public function getName(): string
    {
    }

    public function getStorage(): ResourceStorage
    {
    }

    public function getHashedIdentifier(): string
    {
    }

    public function getParentFolder(): FolderInterface
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\StrictTypesFolderInterfaceRector\Fixture;

use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\Driver\DriverInterface;
use TYPO3\CMS\Core\Resource\FolderInterface;
use TYPO3\CMS\Core\Resource\ResourceStorage;

class MyFolder implements FolderInterface
{
    public function getSubfolders(): array
    {
    }

    public function getSubfolder(string $name): Folder
    {
    }

    public function hasFolder(string $name): bool
    {
    }

    public function hasFile(string $name): bool
    {
    }

    public function getFile(string $fileName): ?FileInterface
    {
    }

    public function rename(string $newName): self
    {
    }

    public function delete(): bool
    {
    }

    public function getModificationTime(): int
    {
    }

    public function getCreationTime(): int
    {
    }

    public function getIdentifier(): string
    {
    }

    public function getName(): string
    {
    }

    public function getStorage(): ResourceStorage
    {
    }

    public function getHashedIdentifier(): string
    {
    }

    public function getParentFolder(): FolderInterface
    {
    }
}

?>
