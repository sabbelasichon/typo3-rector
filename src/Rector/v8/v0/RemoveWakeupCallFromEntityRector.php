<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-72370-RemovedDeprecatedCodeFromExtbase.html
 */
final class RemoveWakeupCallFromEntityRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, AbstractDomainObject::class)) {
            return null;
        }

        if (! $this->isName($node->name, '__wakeup')) {
            return null;
        }

        $this->removeNode($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove __wakeup call for AbstractDomainObject',
            [
                new CodeSample(
                    <<<'PHP'
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
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class MyWakeupCallerClass extends AbstractDomainObject
{
    private $mySpecialResourceAfterWakeUp;

    public function __wakeup()
    {
        $this->mySpecialResourceAfterWakeUp = fopen(__FILE__, 'wb');
    }
}
PHP
                ),
            ]);
    }
}
