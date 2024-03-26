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

namespace TYPO3\CMS\Backend\View\BackendLayout;

/**
 * Interface for classes which hook into BackendLayoutDataProvider
 * to provide additional backend layouts from various sources.
 */
interface DataProviderInterface
{
    /**
     * Adds backend layouts to the given backend layout collection.
     */
    public function addBackendLayouts(DataProviderContext $dataProviderContext, BackendLayoutCollection $backendLayoutCollection);

    /**
     * Gets a backend layout by (regular) identifier.
     *
     * @param string $identifier
     * @param int $pageId
     * @return BackendLayout|null
     */
    public function getBackendLayout($identifier, $pageId);
}
