<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector;

use PhpParser\Node;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ObjectTypeRector extends AbstractRector
{

    public function getRuleDefinition(): RuleDefinition
    {

    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\MethodCall::class];
    }

    /**
     * @param Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isNames($node->name, ['isMethodStaticCallOrClassMethodObjectType', 'isObjectType'])) {
            return null;
        }

        $secondArgumentValue = $node->args[1]->value;
        if ($secondArgumentValue instanceof Node\Expr\New_) {
            return null;
        }

        $newObjectType = new Node\Expr\New_(new Node\Name\FullyQualified(ObjectType::class), [$node->args[1]]);
        $node->args[1]->value = $newObjectType;

        return $node;
    }
}
