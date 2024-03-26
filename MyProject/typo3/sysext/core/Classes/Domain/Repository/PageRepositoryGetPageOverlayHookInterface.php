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

namespace TYPO3\CMS\Core\Domain\Repository;

/**
 * Interface for classes which hook into \TYPO3\CMS\Core\Domain\Repository\PageRepository
 *
 * @deprecated since TYPO3 v12, will be removed in TYPO3 v13.0. Use the PSR-14 events instead.
 */
interface PageRepositoryGetPageOverlayHookInterface
{
    /**
     * enables to preprocess the pageoverlay
     *
     * @param array $pageInput The page record
     * @param int $lUid The overlay language
     * @param PageRepository $parent The calling parent object
     */
    public function getPageOverlay_preProcess(&$pageInput, &$lUid, PageRepository $parent);
}
