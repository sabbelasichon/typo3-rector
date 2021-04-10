<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\IndexedSearch\Controller\SearchFormController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.6.x/Breaking-72931-SearchFormControllerpi_list_browseresultsHasBeenRenamed.html
 */
final class RenamePiListBrowserResultsRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node->var, new ObjectType(SearchFormController::class))) {
            return null;
        }

        if (! $this->isName($node->name, 'pi_list_browseresults')) {
            return null;
        }

        return $this->process($node, 'renderPagination');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rename pi_list_browseresults calls to renderPagination', [
            new CodeSample('$this->pi_list_browseresults', '$this->renderPagination'),
        ]);
    }

    /**
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
