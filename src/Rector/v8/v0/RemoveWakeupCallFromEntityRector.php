<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/8.0/Breaking-72370-RemovedDeprecatedCodeFromExtbase.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v0\RemoveWakeupCallFromEntityRector\RemoveWakeupCallFromEntityRectorTest
 */
final class RemoveWakeupCallFromEntityRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node\Stmt\Expression::class];
    }

    /**
     * @param Node\Stmt\Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $staticCall = $node->expr;

        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject')
        )) {
            return null;
        }

        if (! $this->isName($staticCall->name, '__wakeup')) {
            return null;
        }

        return NodeTraverser::REMOVE_NODE;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove __wakeup call for AbstractDomainObject',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class MyWakeupCallerClass extends AbstractDomainObject
{
    private $mySpecialResourceAfterWakeUp;

    public function __wakeup()
    {
        $this->mySpecialResourceAfterWakeUp = fopen(__FILE__, 'wb');
        parent::__wakeup();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class MyWakeupCallerClass extends AbstractDomainObject
{
    private $mySpecialResourceAfterWakeUp;

    public function __wakeup()
    {
        $this->mySpecialResourceAfterWakeUp = fopen(__FILE__, 'wb');
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }
}
