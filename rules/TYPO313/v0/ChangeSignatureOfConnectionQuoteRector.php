<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102875-ChangedConnectionMethodSignaturesAndBehaviour.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureOfConnectionQuoteRector\ChangeSignatureOfConnectionQuoteRectorTest
 */
final class ChangeSignatureOfConnectionQuoteRector extends AbstractRector implements DocumentedRuleInterface
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $changed = false;

        if (count($node->args) === 2) {
            unset($node->args[1]);
            $changed = true;
        }

        $argument = $node->args[0];
        if (! $argument instanceof Arg) {
            return null;
        }

        $type = $this->getType($argument->value);
        if ($type->isString()->no()) {
            $node->args[0]->value = new String_($argument->value);
            $changed = true;
        }

        return $changed ? $node : null;
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('Doctrine\DBAL\Connection')
        ) && ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Database\Query\QueryBuilder')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'quote');
    }
}
