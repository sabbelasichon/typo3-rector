<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\ComposerPackages\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use Rector\Composer\ValueObject\RenamePackage;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddReplacePackageRector extends AbstractRector
{
    /**
     * @var RenamePackage[]
     */
    private ?array $replacePackges = null;

    public function __construct(
        private SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer
    ) {
    }

    public function setReplacePackages(array $replacePackages): void
    {
        $this->replacePackges = $replacePackages;
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
        if (null === $this->replacePackges) {
            return null;
        }

        if (! $this->symfonyPhpConfigClosureAnalyzer->isPhpConfigClosure($node)) {
            return null;
        }

        if (! property_exists($node, 'stmts')) {
            return null;
        }

        /** @var Expression $stmt */
        foreach ($node->stmts as $stmt) {
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

<<<<<<< HEAD
            foreach ($this->replacePackges as $replacePackage) {
                $stmt->expr->expr->items[] = new ArrayItem(
=======
            $array = $assign->expr;

            foreach ($this->renamePackages as $replacePackage) {
                $array->items[] = new ArrayItem(
>>>>>>> ea19d260... improve type resolving on array expr
                    new New_(
                        new FullyQualified(RenamePackage::class),
                        $this->nodeFactory->createArgs([
                            $replacePackage->getOldPackageName(),
                            $replacePackage->getNewPackageName(),
                        ])
                    )
                );
            }
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
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
     $composerExtensions = [];
};
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
     $composerExtensions = [
        new ReplacePackage('typo3-ter/news', 'georgringer/news')
     ];
};
CODE_SAMPLE
            ),
        ]);
    }
}
