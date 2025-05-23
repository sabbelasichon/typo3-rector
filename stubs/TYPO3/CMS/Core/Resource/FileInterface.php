<?php

namespace TYPO3\CMS\Core\Resource;

if (interface_exists('TYPO3\CMS\Core\Resource\FileInterface')) {
    return;
}

interface FileInterface extends ResourceInterface
{
    public function hasProperty(string $key): bool;
    public function getProperty(string $key): mixed;

    public function getSize();

    public function getSha1();

    public function getNameWithoutExtension();

    public function getExtension();

    public function getMimeType();

    public function getModificationTime();

    public function getCreationTime();

    public function getContents();

    public function delete();

    public function rename($newName, $conflictMode);

    public function isIndexed();

    public function getForLocalProcessing($writable = true);

    public function toArray();

    public function getPublicUrl();
    public function setContents($contents);
}
