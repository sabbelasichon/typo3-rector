<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

final class ChangeAttemptsParameterConsoleOutputRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|MethodCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node, ConsoleOutput::class)) {
            return null;
        }

        if (!$this->isName($node, 'select') && !$this->isName($node, 'askAndValidate')) {
            return null;
        }

        if ($this->isName($node, 'select') && count($node->args) < 5) {
            return null;
        }

        if ($this->isName($node, 'askAndValidate') && count($node->args) < 3) {
            return null;
        }

        $arguments = $node->args;

        // Change the argument for attempts if it false to null
        if ($this->isName($node, 'askAndValidate') && 'false' === $this->getName($arguments[2]->value)) {
            $arguments[2] = null;
        } elseif ($this->isName($node, 'select') && 'false' === $this->getName($arguments[4]->value)) {
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
