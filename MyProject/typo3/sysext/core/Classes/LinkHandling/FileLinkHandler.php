<?php

declare(strict_types=1);

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

namespace TYPO3\CMS\Core\LinkHandling;

use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Interface for classes which are transforming a tag link hrefs for folders, in order to
 * use FAL to store them in database, which means that files can be moved in the fileadmin
 * without breaking file links in the frontend/backend
 */
class FileLinkHandler implements LinkHandlingInterface
{
    /**
     * The Base URN
     */
    protected string $baseUrn = 't3://file';

    /**
     * The resource factory object to resolve file objects
     */
    protected ResourceFactory $resourceFactory;

    /**
     * Returns the link to a file as a string
     */
    public function asString(array $parameters): string
    {
        if ($parameters['file'] === null) {
            return '';
        }
        $uid = $parameters['file']->getUid();
        // I am not sure about this use case. Maybe if the file was not indexed and saved to DB (migration from old systems)
        if ($uid > 0) {
            $urn = '?uid=' . $uid;
        } else {
            $identifier = $parameters['file']->getIdentifier();
            $urn = '?identifier=' . urlencode($identifier);
        }
        if (!empty($parameters['fragment'])) {
            $urn .= '#' . $parameters['fragment'];
        }
        return $this->baseUrn . $urn;
    }

    /**
     * Get a file object inside the array data from the string
     *
     * @param array $data with the "file" property containing a File object
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     */
    public function resolveHandlerData(array $data): array
    {
        try {
            $file = $this->resolveFile($data);
        } catch (FileDoesNotExistException $e) {
            $file = null;
        }
        $result = ['file' => $file];
        if (!empty($data['fragment'])) {
            $result['fragment'] = $data['fragment'];
        }
        return $result;
    }

    /**
     * @throws FileDoesNotExistException
     */
    protected function resolveFile(array $data): ?FileInterface
    {
        if (is_numeric($data['uid'] ?? false)) {
            return $this->getResourceFactory()->getFileObject($data['uid']);
        }
        if (is_string($data['identifier'] ?? false) && $data['identifier'] !== '') {
            return $this->getResourceFactory()->getFileObjectFromCombinedIdentifier($data['identifier']);
        }
        return null;
    }

    /**
     * Initializes the resource factory (only once)
     */
    protected function getResourceFactory(): ResourceFactory
    {
        return $this->resourceFactory ??= GeneralUtility::makeInstance(ResourceFactory::class);
    }
}
