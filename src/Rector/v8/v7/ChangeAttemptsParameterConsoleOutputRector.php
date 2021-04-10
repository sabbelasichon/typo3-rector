<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80053-ExtbaseCLIConsoleOutputDifferentMethodSignatureForInfiniteAttempts.html
 */
final class ChangeAttemptsParameterConsoleOutputRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ASK_AND_VALIDATE = 'askAndValidate';

    /**
     * @var string
     */
    private const SELECT = 'select';

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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(ConsoleOutput::class)
        )) {
            return null;
        }
        if (! $this->isName($node->name, self::SELECT) && ! $this->isName($node->name, self::ASK_AND_VALIDATE)) {
            return null;
        }
        if ($this->isName($node->name, self::SELECT) && count($node->args) < 5) {
            return null;
        }
        if ($this->isName($node->name, self::ASK_AND_VALIDATE) && count($node->args) < 3) {
            return null;
        }
        $arguments = $node->args;
        // Change the argument for attempts if it false to null
        if ($this->isName($node->name, self::ASK_AND_VALIDATE) && 'false' === $this->getName($arguments[2]->value)) {
            $arguments[2] = null;
        } elseif ($this->isName($node->name, self::SELECT) && 'false' === $this->getName($arguments[4]->value)) {
            $arguments[4] = null;
        }
        $node->args = $this->nodeFactory->createArgs($arguments);
        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
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
