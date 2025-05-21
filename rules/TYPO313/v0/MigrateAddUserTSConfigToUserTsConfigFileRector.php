<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\ExtensionKeyResolverTrait;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Deprecation-101807-ExtensionManagementUtilityaddUserTSConfig.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateAddUserTSConfigToUserTsConfigFileRector\MigrateAddUserTSConfigToUserTsConfigFileRectorTest
 */
final class MigrateAddUserTSConfigToUserTsConfigFileRector extends AbstractRector implements DocumentedRuleInterface
{
    use ExtensionKeyResolverTrait;

    /**
     * @readonly
     */
    private FilesFinder $filesFinder;

    /**
     * @readonly
     */
    private FilesystemInterface $filesystem;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        ComposerExtensionKeyResolver $composerExtensionKeyResolver
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->composerExtensionKeyResolver = $composerExtensionKeyResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate method call `ExtensionManagementUtility::addUserTSConfig()` to user.tsconfig', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig(
    '@import "EXT:extension_key/Configuration/TSconfig/*/*.tsconfig"'
);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// Move to file Configuration/user.tsconfig
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        $staticMethodCall = $node->expr;
        if (! $staticMethodCall instanceof StaticCall) {
            return null;
        }

        if ($this->shouldSkip($staticMethodCall)) {
            return null;
        }

        $contentArgument = $staticMethodCall->args[0] ?? null;
        if ($contentArgument === null) {
            return null;
        }

        $contentArgumentValue = $contentArgument->value;
        if (! $contentArgumentValue instanceof String_ && ! $contentArgumentValue instanceof Concat) {
            return null;
        }

        $this->resolvePotentialExtensionKeyByConcatenation($contentArgumentValue);

        $directoryName = dirname($this->file->getFilePath());

        $content = $this->valueResolver->getValue($contentArgumentValue);
        $newConfigurationFile = $directoryName . '/Configuration/user.tsconfig';
        if (str_contains($content, '/Configuration/user.tsconfig')) {
            return NodeVisitor::REMOVE_NODE;
        }

        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
{$content}

CODE
            );
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        if (! $this->isName($staticMethodCall->name, 'addUserTSConfig')) {
            return true;
        }

        return ! $this->filesFinder->isExtLocalConf($this->file->getFilePath());
    }
}
