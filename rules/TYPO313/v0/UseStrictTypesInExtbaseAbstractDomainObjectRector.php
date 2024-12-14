<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102632-UseStrictTypesInExtbase.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\UseStrictTypesInExtbaseAbstractDomainObjectRector\UseStrictTypesInExtbaseAbstractDomainObjectRectorTest
 */
final class UseStrictTypesInExtbaseAbstractDomainObjectRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use strict types in Extbase AbstractDomainObject', [new CodeSample(
            <<<'CODE_SAMPLE'
abstract class AbstractDomainObject
{
    protected $uid;
    protected $pid;
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
abstract class AbstractDomainObject
{
    protected ?int $uid = null;
    protected ?int $pid = null;
}
CODE_SAMPLE
        )]);
    }

    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
