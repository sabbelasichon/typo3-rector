<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\IndexedSearch\Controller;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\IndexedSearch\Controller\SearchFormController;

final class RenamePiListBrowserResultsRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, SearchFormController::class)) {
            return null;
        }

        if (!$this->isName($node, 'pi_list_browseresults')) {
            return null;
        }

        $newNode = $this->process($node, 'renderPagination');
        if (null !== $newNode) {
            return $newNode;
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Rename pi_list_browseresults calls to renderPagination',
            [
                new CodeSample(
                    '$this->pi_list_browseresults',
                    '$this->renderPagination'
                ),
            ]
        );
    }

    /**
     * @param MethodCall $node
     * @param string|mixed[] $newMethod
     *
     * @return MethodCall|ArrayDimFetch
     */
    private function process(MethodCall $node, $newMethod): Node
    {
        if (is_string($newMethod)) {
            $node->name = new Identifier($newMethod);

            return $node;
        }

        // special case for array dim fetch
        $node->name = new Identifier($newMethod['name']);

        return new ArrayDimFetch($node, BuilderHelpers::normalizeValue($newMethod['array_key']));
    }
}
