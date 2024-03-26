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

namespace TYPO3\CMS\Core\TypoScript\AST\Visitor;

use TYPO3\CMS\Core\TypoScript\AST\CurrentObjectPath\CurrentObjectPath;
use TYPO3\CMS\Core\TypoScript\AST\Node\NodeInterface;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;

/**
 * Sort all children alphabetically. Used in backend Object Browser.
 *
 * @internal: Internal AST structure.
 */
final class AstSortChildrenVisitor implements AstVisitorInterface
{
    public function visitBeforeChildren(RootNode $rootNode, NodeInterface $node, CurrentObjectPath $currentObjectPath, int $currentDepth): void
    {
        $node->sortChildren();
    }

    public function visit(RootNode $rootNode, NodeInterface $node, CurrentObjectPath $currentObjectPath, int $currentDepth): void
    {
        // Implement interface
    }

    public function visitAfterChildren(RootNode $rootNode, NodeInterface $node, CurrentObjectPath $currentObjectPath, int $currentDepth): void
    {
        // Implement interface
    }
}
