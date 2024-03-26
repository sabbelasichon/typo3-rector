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

namespace TYPO3\CMS\Frontend\ContentObject;

/**
 * interface for classes which hook into \TYPO3\CMS\Frontend\ContentObjectRenderer and wish to modify the typolink
 * configuration of the page link.
 * @deprecated not in use anymore since TYPO3 v12, will be removed in TYPO3 13. Only stays for adding compatibility for TYPO3 v11+v12 compatible extensions
 */
interface TypolinkModifyLinkConfigForPageLinksHookInterface
{
    /**
     * Modifies the typolink page link configuration array.
     *
     * @param array $linkConfiguration The link configuration (for options see TSRef -> typolink)
     * @param array $linkDetails Additional information for the link
     * @param array $pageRow The complete page row for the page to link to
     *
     * @return array The modified $linkConfiguration
     */
    public function modifyPageLinkConfiguration(array $linkConfiguration, array $linkDetails, array $pageRow): array;
}
