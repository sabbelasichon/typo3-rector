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

namespace TYPO3\CMS\Backend\Tree\View;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Generate a page-tree, non-browsable.
 *
 * @internal This class is a TYPO3 Backend implementation and is not considered part of the Public TYPO3 API.
 */
class PageTreeView extends AbstractTreeView
{
    /**
     * Init function
     * REMEMBER to feed a $clause which will filter out non-readable pages!
     *
     * @param string $clause Part of where query which will filter out non-readable pages.
     * @param string $orderByFields Record ORDER BY field
     */
    public function init($clause = '', $orderByFields = '')
    {
        parent::init(' AND deleted=0 AND sys_language_uid=0 ' . $clause, $orderByFields ?: 'sorting');
    }

    /**
     * Returns TRUE/FALSE if the next level for $id should be expanded - and all levels should, so we always return 1.
     *
     * @param int $id ID (uid) to test for (see extending classes where this is checked against session data)
     * @return bool
     */
    public function expandNext($id)
    {
        return true;
    }

    /**
     * Generate the plus/minus icon for the browsable tree.
     * In this case, there is no plus-minus icon displayed.
     *
     * @param array $row Record for the entry
     * @param int $a The current entry number
     * @param int $c The total number of entries. If equal to $a, a 'bottom' element is returned.
     * @param int $nextCount The number of sub-elements to the current element.
     * @param bool $isExpand The element was expanded to render subelements if this flag is set.
     * @return string Image tag with the plus/minus icon.
     * @internal
     * @see AbstractTreeView::PMicon()
     */
    public function PMicon($row, $a, $c, $nextCount, $isExpand)
    {
        return '<span class="treeline-icon treeline-icon-join' . ($a == $c ? 'bottom' : '') . '"></span>';
    }

    /**
     * Returns the title for the input record. If blank, a "no title" label (localized) will be returned.
     * Do NOT htmlspecialchar the string from this function - has already been done.
     *
     * @param array $row The input row array (where the key "title" is used for the title)
     * @param int $titleLen Title length (30)
     * @return string The title.
     */
    public function getTitleStr($row, $titleLen = 30)
    {
        $lang = $this->getLanguageService();
        $title = htmlspecialchars(GeneralUtility::fixed_lgd_cs($row['title'], (int)$titleLen));
        if (isset($row['nav_title']) && trim($row['nav_title']) !== '') {
            $title = '<span title="'
                        . htmlspecialchars($lang->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.nav_title'))
                        . ' ' . htmlspecialchars(trim($row['nav_title'])) . '">' . $title
                        . '</span>';
        }
        return trim($row['title']) === ''
            ? '<em>[' . htmlspecialchars($lang->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:labels.no_title')) . ']</em>'
            : $title;
    }
}
