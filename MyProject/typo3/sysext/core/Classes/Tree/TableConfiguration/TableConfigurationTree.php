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

namespace TYPO3\CMS\Core\Tree\TableConfiguration;

use TYPO3\CMS\Backend\Tree\AbstractTree;

/**
 * Class for tca tree
 */
class TableConfigurationTree extends AbstractTree
{
    /**
     * Returns the root node
     *
     * @return \TYPO3\CMS\Backend\Tree\TreeNode
     */
    public function getRoot()
    {
        return $this->dataProvider->getRoot();
    }

    /**
     * Renders a tree
     *
     * @return mixed
     */
    public function render()
    {
        return $this->nodeRenderer->renderTree($this);
    }
}
