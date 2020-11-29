<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v3;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-89870-NewPSR-14EventsForExtbase-relatedSignals.html
 */
final class UsePSR14EventsExtbaseRelatedSignalsRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ActionController::class)) {
            return null;
        }

        if (null === $node->name) {
            return null;
        }

        if ($this->isName($node->name, 'emitBeforeCallActionMethodSignal')) {
            if (! (property_exists($node, 'args') && null !== $node->args)) {
                return null;
            }

            /** @var Arg[] $args */
            $args = $node->args;
            $firstArgument = array_shift($args);

            if (null === $firstArgument) {
                return null;
            }

            $argumentValue = $this->getValue($firstArgument->value);

            if (null === $argumentValue) {
                return null;
            }

            return $this->createMethodCall($this->createPropertyFetch($node->var, 'eventDispatcher'), 'dispatch',
                [
                    $this->createMethodCall('new', 'BeforeActionCallEvent', [
                        'static::class',
                        'indexAction',
                        $firstArgument,
                    ]),
                ]
            );
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use PSR-14 Events for Extbase related signal',
            [new CodeSample(<<<'PHP'
PHP
            , <<<'PHP'
PHP
        )]);
    }
}
