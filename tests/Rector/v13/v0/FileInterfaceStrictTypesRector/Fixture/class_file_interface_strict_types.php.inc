<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;

class MyFile implements FileInterface
{

    public function hasProperty($key)
    {
    }

    public function getProperty($key)
    {
    }

    public function getSize()
    {

    }

    public function getSha1()
    {

    }

    public function getNameWithoutExtension()
    {

    }

    public function getExtension()
    {

    }

    public function getMimeType()
    {

    }

    public function getModificationTime()
    {

    }

    public function getCreationTime()
    {

    }

    public function getContents()
    {

    }

    public function delete()
    {

    }

    public function rename($newName, $conflictMode)
    {

    }

    public function isIndexed()
    {

    }

    public function getForLocalProcessing($writable = true)
    {

    }

    public function toArray()
    {

    }

    public function getPublicUrl()
    {

    }
    public function setContents($contents)
    {

    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ResourceInterfaceStrictTypesRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;

class MyFile implements FileInterface
{

    public function hasProperty(string $key): bool
    {
    }

    public function getProperty(string $key): mixed
    {
    }

    public function getSize(): int
    {

    }

    public function getSha1(): string
    {

    }

    public function getNameWithoutExtension(): string
    {

    }

    public function getExtension(): string
    {

    }

    public function getMimeType(): string
    {

    }

    public function getModificationTime(): int
    {

    }

    public function getCreationTime(): int
    {

    }

    public function getContents(): string
    {

    }

    public function delete(): bool
    {

    }

    public function rename(string $newName, $conflictMode): FileInterface
    {

    }

    public function isIndexed(): bool
    {

    }

    public function getForLocalProcessing(bool $writable = true): string
    {

    }

    public function toArray(): array
    {

    }

    public function getPublicUrl(): ?string
    {

    }
    public function setContents(string $contents): self
    {

    }
}

?>
