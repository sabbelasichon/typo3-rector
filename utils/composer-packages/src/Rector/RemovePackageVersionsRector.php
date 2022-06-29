<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Ssch\TYPO3Rector\ComposerPackages\Tests\Rector\RemovePackageVersionsRector\RemovePackageVersionsRectorTest
 */
final class RemovePackageVersionsRector extends AbstractRector
{
    /**
     * @readonly
     */
    private SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer;

    public function __construct(SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer)
    {
        $this->symfonyPhpConfigClosureAnalyzer = $symfonyPhpConfigClosureAnalyzer;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Closure::class];
    }

    /**
     * @param Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->symfonyPhpConfigClosureAnalyzer->isPhpConfigClosure($node)) {
            return null;
        }

        /** @var Closure $closure */
        $closure = $node;

        /** @var Expression $stmt */
        foreach ($closure->stmts as $stmt) {
            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;

            if (! $this->isName($assign->var, 'composerExtensions')) {
                continue;
            }

            if (! $assign->expr instanceof Array_) {
                continue;
            }

            $array = $assign->expr;
            $array->items = [];
        }

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add PackageAndVersion entry for an extension', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
     $composerExtensions = [
        new PackageAndVersion('foo/bar', '^1.0')
     ];
};
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
     $composerExtensions = [];
};
CODE_SAMPLE
            ),
        ]);
    }
}
