<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use Doctrine\DBAL\Connection;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102875-ChangedConnectionMethodSignaturesAndBehaviour.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSingnatureOfConnectionQuoteRector\ChangeSingnatureOfConnectionQuoteRectorTest
 */
final class ChangeSingnatureOfConnectionQuoteRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Ensure first parameter is of type string and remove second parameter', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(Connection::class);
$connection->quote(1, 1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$connection = GeneralUtility::makeInstance(Connection::class);
$connection->quote((string) 1);
CODE_SAMPLE
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(Connection::class)
        ) && ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(QueryBuilder::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'quote')) {
            return null;
        }

        if (count($node->args) === 2) {
            unset($node->args[1]);
        }

        /** @var Node\Arg $argument */
        $argument = $node->args[0];
        $value = $argument->value;
        $type = $value->getType();
        if ($type !== 'Scalar_String') {
            $node->args[0]->value = new Node\Expr\Cast\String_($value);
        }

        return $node;
    }
}
