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
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class AddReplacePackageRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var RenamePackage[]
     */
    private ?array $renamePackages = null;

    public function __construct(
        private SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer
    ) {
    }

    /**
     * @param RenamePackage[] $renamePackages
     */
    public function configure(array $renamePackages): void
    {
        Assert::allIsAOf($renamePackages, RenamePackage::class);
        $this->renamePackages = $renamePackages;
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
        if (null === $this->renamePackages) {
            return null;
        }

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

            foreach ($this->renamePackages as $renamePackage) {
                $array->items[] = new ArrayItem(
                    new New_(
                        new FullyQualified(RenamePackage::class),
                        $this->nodeFactory->createArgs([
                            $renamePackage->getOldPackageName(),
                            $renamePackage->getNewPackageName(),
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
            new ConfiguredCodeSample(
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
                ,
                [new RenamePackage('typo3-ter/news', 'georgringer/news')]
            ),
        ]);
    }
}
