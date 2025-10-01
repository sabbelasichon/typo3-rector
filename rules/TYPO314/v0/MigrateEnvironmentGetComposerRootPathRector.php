<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107482-EnvironmentGetComposerRootPathMethodsRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateEnvironmentGetComposerRootPathRector\MigrateEnvironmentGetComposerRootPathRectorTest
 */
final class MigrateEnvironmentGetComposerRootPathRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `Environment::getComposerRootPath()` to `Environment::getProjectPath()`', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Core\Environment::getComposerRootPath();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Core\Environment::getProjectPath();
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->class, 'TYPO3\CMS\Core\Core\Environment')) {
            return null;
        }

        if (! $this->isName($node->name, 'getComposerRootPath')) {
            return null;
        }

        $node->name = new Identifier('getProjectPath');

        return $node;
    }
}
