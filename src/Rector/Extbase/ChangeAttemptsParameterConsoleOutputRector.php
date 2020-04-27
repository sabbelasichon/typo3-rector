<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80053-ExtbaseCLIConsoleOutputDifferentMethodSignatureForInfiniteAttempts.html
 */
final class ChangeAttemptsParameterConsoleOutputRector extends AbstractRector
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ConsoleOutput::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'select') && ! $this->isName($node->name, 'askAndValidate')) {
            return null;
        }

        if ($this->isName($node->name, 'select') && count($node->args) < 5) {
            return null;
        }

        if ($this->isName($node->name, 'askAndValidate') && count($node->args) < 3) {
            return null;
        }

        $arguments = $node->args;

        // Change the argument for attempts if it false to null
        if ($this->isName($node->name, 'askAndValidate') && 'false' === $this->getName($arguments[2]->value)) {
            $arguments[2] = null;
        } elseif ($this->isName($node->name, 'select') && 'false' === $this->getName($arguments[4]->value)) {
            $arguments[4] = null;
        }

        $node->args = $this->createArgs($arguments);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns old default value to parameter in ConsoleOutput->askAndValidate() and/or ConsoleOutput->select() method',
            [
                new CodeSample(
                    '$this->output->select(\'The question\', [1, 2, 3], null, false, false);',
                    '$this->output->select(\'The question\', [1, 2, 3], null, false, null);'
                ),
            ]
        );
    }
}
