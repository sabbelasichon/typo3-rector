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

namespace TYPO3\CMS\Core\Resource;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Representation of a specific usage of a file with possibilities to override certain
 * properties of the original file just for this usage of the file.
 *
 * It acts as a decorator over the original file in the way that most method calls are
 * directly passed along to the original file object.
 *
 * All file related methods are directly passed along; only meta data functionality is adopted
 * in this decorator class to priorities possible overrides for the metadata for this specific usage
 * of the file.
 */
class FileReference implements FileInterface
{
    /**
     * Various properties of the FileReference. Note that these information can be different
     * to the ones found in the originalFile.
     *
     * @var array
     */
    protected $propertiesOfFileReference;

    /**
     * Reference to the original File object underlying this FileReference.
     *
     * @var FileInterface
     */
    protected $originalFile;

    /**
     * Properties merged with the parent object (File) if
     * the value is not defined (NULL). Thus, FileReference properties act
     * as overlays for the defined File properties.
     *
     * @var array
     */
    protected $mergedProperties = [];

    /**
     * Constructor for a file in use object. Should normally not be used
     * directly, use the corresponding factory methods instead.
     *
     * @param ResourceFactory $factory
     *
     * @throws \InvalidArgumentException
     * @throws Exception\FileDoesNotExistException
     */
    public function __construct(array $fileReferenceData, $factory = null)
    {
        $this->propertiesOfFileReference = $fileReferenceData;
        if (!$fileReferenceData['uid_local']) {
            throw new \InvalidArgumentException('Incorrect reference to original file given for FileReference.', 1300098528);
        }
        $this->originalFile = $this->getFileObject((int)$fileReferenceData['uid_local'], $factory);
    }

    /**
     * @param ResourceFactory|null $factory
     * @throws Exception\FileDoesNotExistException
     */
    private function getFileObject(int $uidLocal, ResourceFactory $factory = null): FileInterface
    {
        if ($factory === null) {
            $factory = GeneralUtility::makeInstance(ResourceFactory::class);
        }
        return $factory->getFileObject($uidLocal);
    }

    /*******************************
     * VARIOUS FILE PROPERTY GETTERS
     *******************************/
    /**
     * Returns true if the given key exists for this file.
     *
     * @param string $key The property to be looked up
     * @return bool
     */
    public function hasProperty($key)
    {
        return array_key_exists($key, $this->getProperties());
    }

    /**
     * Gets a property, falling back to values of the parent.
     *
     * @param string $key The property to be looked up
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getProperty($key)
    {
        if (!$this->hasProperty($key)) {
            throw new \InvalidArgumentException('Property "' . $key . '" was not found in file reference or original file.', 1314226805);
        }
        $properties = $this->getProperties();
        return $properties[$key];
    }

    /**
     * Gets a property of the file reference.
     *
     * @param string $key The property to be looked up
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function getReferenceProperty($key)
    {
        if (!array_key_exists($key, $this->propertiesOfFileReference)) {
            throw new \InvalidArgumentException('Property "' . $key . '" of file reference was not found.', 1360684914);
        }
        return $this->propertiesOfFileReference[$key];
    }

    /**
     * Gets all properties, falling back to values of the parent.
     *
     * @return array
     */
    public function getProperties()
    {
        if (empty($this->mergedProperties)) {
            $this->mergedProperties = $this->propertiesOfFileReference;
            ArrayUtility::mergeRecursiveWithOverrule(
                $this->mergedProperties,
                $this->originalFile->getProperties(),
                true,
                true,
                false
            );
            array_walk($this->mergedProperties, [$this, 'restoreNonNullValuesCallback']);
        }

        return $this->mergedProperties;
    }

    /**
     * Callback to handle the NULL value feature
     *
     * @param mixed $value
     * @param mixed $key
     */
    protected function restoreNonNullValuesCallback(&$value, $key)
    {
        if (array_key_exists($key, $this->propertiesOfFileReference) && $this->propertiesOfFileReference[$key] !== null) {
            $value = $this->propertiesOfFileReference[$key];
        }
    }

    /**
     * Gets all properties of the file reference.
     *
     * @return array
     */
    public function getReferenceProperties()
    {
        return $this->propertiesOfFileReference;
    }

    /**
     * Returns the name of this file
     *
     * @return string
     */
    public function getName()
    {
        return $this->originalFile->getName();
    }

    /**
     * Returns the title text to this image
     *
     * @todo Possibly move this to the image domain object instead
     *
     * @return string
     */
    public function getTitle()
    {
        return (string)$this->getProperty('title');
    }

    /**
     * Returns the alternative text to this image
     *
     * @todo Possibly move this to the image domain object instead
     *
     * @return string
     */
    public function getAlternative()
    {
        return (string)$this->getProperty('alternative');
    }

    /**
     * Returns the description text to this file
     *
     * @todo Possibly move this to the image domain object instead
     *
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->getProperty('description');
    }

    /**
     * Returns the link that should be active when clicking on this image
     *
     * @todo Move this to the image domain object instead
     *
     * @return string
     */
    public function getLink()
    {
        return $this->propertiesOfFileReference['link'];
    }

    /**
     * Returns the uid of this File In Use
     *
     * @return int
     */
    public function getUid()
    {
        return (int)$this->propertiesOfFileReference['uid'];
    }

    /**
     * Returns the size of this file
     *
     * @return int
     */
    public function getSize()
    {
        return (int)$this->originalFile->getSize();
    }

    /**
     * Returns the Sha1 of this file
     *
     * @return string
     */
    public function getSha1()
    {
        return $this->originalFile->getSha1();
    }

    /**
     * Get the file extension of this file
     *
     * @return string The file extension
     */
    public function getExtension()
    {
        return $this->originalFile->getExtension();
    }

    /**
     * Returns the basename (the name without extension) of this file.
     *
     * @return string
     */
    public function getNameWithoutExtension()
    {
        return $this->originalFile->getNameWithoutExtension();
    }

    /**
     * Get the MIME type of this file
     *
     * @return string mime type
     */
    public function getMimeType()
    {
        return $this->originalFile->getMimeType();
    }

    /**
     * Returns the modification time of the file as Unix timestamp
     *
     * @return int
     */
    public function getModificationTime()
    {
        return (int)$this->originalFile->getModificationTime();
    }

    /**
     * Returns the creation time of the file as Unix timestamp
     *
     * @return int
     */
    public function getCreationTime()
    {
        return (int)$this->originalFile->getCreationTime();
    }

    /**
     * Returns the fileType of this file
     *
     * @return int $fileType
     */
    public function getType()
    {
        return (int)$this->originalFile->getType();
    }

    /**
     * Check if file is marked as missing by indexer
     *
     * @return bool
     */
    public function isMissing()
    {
        return (bool)$this->originalFile->getProperty('missing');
    }

    /******************
     * CONTENTS RELATED
     ******************/
    /**
     * Get the contents of this file
     *
     * @return string File contents
     */
    public function getContents()
    {
        return $this->originalFile->getContents();
    }

    /**
     * Replace the current file contents with the given string
     *
     * @param string $contents The contents to write to the file.
     * @return File The file object (allows chaining).
     */
    public function setContents($contents)
    {
        return $this->originalFile->setContents($contents);
    }

    /****************************************
     * STORAGE AND MANAGEMENT RELATED METHODS
     ****************************************/
    /**
     * Get the storage the original file is located in
     *
     * @return ResourceStorage
     */
    public function getStorage()
    {
        return $this->originalFile->getStorage();
    }

    /**
     * Returns the identifier of the underlying original file
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->originalFile->getIdentifier();
    }

    /**
     * Returns a combined identifier of the underlying original file
     *
     * @return string Combined storage and file identifier, e.g. StorageUID:path/and/fileName.png
     */
    public function getCombinedIdentifier()
    {
        return $this->originalFile->getCombinedIdentifier();
    }

    /**
     * Deletes only this particular FileReference from the persistence layer (table: sys_file_reference)
     * and leaves the original file untouched.
     */
    public function delete()
    {
        $deletedRows = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_file_reference')
            ->delete(
                'sys_file_reference',
                [
                    'uid' => $this->getUid(),
                ]
            );

        return $deletedRows === 1;
    }

    /**
     * Renames the fileName in this particular usage.
     *
     * @param string $newName The new name
     * @param string $conflictMode
     */
    public function rename($newName, $conflictMode = DuplicationBehavior::RENAME)
    {
        // @todo Implement this function. This should only rename the
        // FileReference (sys_file_reference) record, not the file itself.
        throw new \BadMethodCallException('Function not implemented FileReference::rename().', 1333754473);
        //return $this->fileRepository->renameUsageRecord($this, $newName);
    }

    /*****************
     * SPECIAL METHODS
     *****************/
    /**
     * Returns a publicly accessible URL for this file
     *
     * WARNING: Access to the file may be restricted by further means, e.g.
     * some web-based authentication. You have to take care of this yourself.
     *
     * @return string|null NULL if file is missing or deleted, the generated url otherwise
     */
    public function getPublicUrl()
    {
        return $this->originalFile->getPublicUrl();
    }

    /**
     * Returns TRUE if this file is indexed.
     * This is always true for FileReference objects, as they rely on a
     * sys_file_reference record to be present, which in turn can only exist if
     * the original file is indexed.
     *
     * @return bool
     */
    public function isIndexed()
    {
        return true;
    }

    /**
     * Returns a path to a local version of this file to process it locally (e.g. with some system tool).
     * If the file is normally located on a remote storages, this creates a local copy.
     * If the file is already on the local system, this only makes a new copy if $writable is set to TRUE.
     *
     * @param bool $writable Set this to FALSE if you only want to do read operations on the file.
     * @return string
     */
    public function getForLocalProcessing($writable = true)
    {
        return $this->originalFile->getForLocalProcessing($writable);
    }

    /**
     * Returns an array representation of the file.
     * (This is used by the generic listing module vidi when displaying file records.)
     *
     * @return array Array of main data of the file. Don't rely on all data to be present here, it's just a selection of the most relevant information.
     */
    public function toArray()
    {
        $array = array_merge($this->originalFile->toArray(), $this->propertiesOfFileReference);
        return $array;
    }

    /**
     * Gets the original file being referenced.
     *
     * @return File
     */
    public function getOriginalFile()
    {
        return $this->originalFile;
    }

    /**
     * Get hashed identifier
     *
     * @return string
     */
    public function getHashedIdentifier()
    {
        return $this->getStorage()->hashFileIdentifier($this->getIdentifier());
    }

    /**
     * Returns the parent folder.
     *
     * @return FolderInterface
     */
    public function getParentFolder()
    {
        return $this->originalFile->getParentFolder();
    }

    /**
     * Avoids exporting original file object which contains
     * singleton dependencies that must not be serialized.
     *
     * @return string[]
     */
    public function __sleep(): array
    {
        $keys = get_object_vars($this);
        unset($keys['originalFile'], $keys['mergedProperties']);
        return array_keys($keys);
    }

    public function __wakeup(): void
    {
        $factory = GeneralUtility::makeInstance(ResourceFactory::class);
        $this->originalFile = $this->getFileObject(
            (int)$this->propertiesOfFileReference['uid_local'],
            $factory
        );
    }
}
