<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

final class RemoveInternalAnnotationRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Class_::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, CommandController::class)) {
            return null;
        }

        if (!$this->docBlockManipulator->hasTag($node, 'internal')) {
            return null;
        }

        $this->docBlockManipulator->removeTagFromNode($node, 'internal');

        return $node;
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove @internal annotation from classes extending \TYPO3\CMS\Extbase\Mvc\Controller\CommandController',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
/**
 * @internal
 */
class MyCommandController extends CommandController
{
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyCommandController extends CommandController
{
}

CODE_SAMPLE
                ),
            ]
        );
    }
}
