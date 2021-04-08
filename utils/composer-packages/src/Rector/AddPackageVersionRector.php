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
use Rector\Composer\ValueObject\PackageAndVersion;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerPackages\NodeAnalyzer\SymfonyPhpConfigClosureAnalyzer;
use Ssch\TYPO3Rector\ComposerPackages\Tests\Rector\AddPackageVersionRector\AddPackageVersionRectorTest;
use Ssch\TYPO3Rector\ComposerPackages\ValueObject\ExtensionVersion;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see AddPackageVersionRectorTest
 */
final class AddPackageVersionRector extends AbstractRector
{
    /**
     * @var ExtensionVersion
     */
    private $extensionVersion;

    /**
     * @var SymfonyPhpConfigClosureAnalyzer
     */
    private $symfonyPhpConfigClosureAnalyzer;

    public function __construct(SymfonyPhpConfigClosureAnalyzer $symfonyPhpConfigClosureAnalyzer)
    {
        $this->symfonyPhpConfigClosureAnalyzer = $symfonyPhpConfigClosureAnalyzer;
    }

    public function setExtension(ExtensionVersion $extensionVersion): void
    {
        $this->extensionVersion = $extensionVersion;
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
        if (null === $this->extensionVersion) {
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

            if (! $this->isName($stmt->expr->var, 'composerExtensions')) {
                continue;
            }

            if (! $stmt->expr->expr instanceof Array_) {
                continue;
            }

            if (! property_exists($stmt->expr->expr, 'items')) {
                continue;
            }

            $stmt->expr->expr->items[] = new ArrayItem(
                new New_(
                    new FullyQualified(PackageAndVersion::class),
                    $this->nodeFactory->createArgs([
                        $this->extensionVersion->packageName(),
                        sprintf('^%s', ltrim($this->extensionVersion->version(), 'v')),
                    ])
                )
            );
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
        new PackageAndVersion('foo/bar', '^1.0')
     ];
};
CODE_SAMPLE
            ),
        ]);
    }
}
