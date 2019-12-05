<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

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

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

final class RemoveFlushCachesRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        $name = $this->getName($node);

        if ('Command' !== substr($name, -7)) {
            return null;
        }

        if (!$this->docBlockManipulator->hasTag($node, 'flushCaches')) {
            return null;
        }

        $this->docBlockManipulator->removeTagFromNode($node, 'flushCaches');

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove @flushesCaches annotation',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * My command
 *
 * @flushesCaches
 */
public function myCommand()
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
/**
 * My Command
 */
public function myCommand()
{
}

CODE_SAMPLE
                ),
            ]
        );
    }
}
