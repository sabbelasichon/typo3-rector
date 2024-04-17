<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\ExtensionKeyResolverTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://review.typo3.org/c/Packages/TYPO3.CMS/+/52437
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\Rector\General\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector\MoveExtensionUtilityRegisterPluginIntoTCAOverridesRectorTest
 */
final class MoveExtensionUtilityRegisterPluginIntoTCAOverridesRector extends AbstractRector
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
    private BetterStandardPrinter $betterStandardPrinter;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ComposerExtensionKeyResolver $composerExtensionKeyResolver,
        BetterStandardPrinter $betterStandardPrinter
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->composerExtensionKeyResolver = $composerExtensionKeyResolver;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move ExtensionUtility::registerPlugin into Configuration/TCA/Overrides/tt_content.php',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('extension_key', 'Pi1', 'Title');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
// Move to file Configuration/TCA/Overrides/tt_content.php
CODE_SAMPLE
                ),
            ]
        );
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
        if (! $contentArgumentValue instanceof String_ && ! $contentArgumentValue instanceof Variable) {
            return null;
        }

        $extensionKey = $this->resolvePotentialExtensionKeyByExtensionKeyParameter($contentArgumentValue);
        if ($extensionKey instanceof String_) {
            $contentArgument->value = $extensionKey;
        }

        $content = $this->betterStandardPrinter->prettyPrint([$staticMethodCall]) . ';';

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/tt_content.php';
        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }

        return NodeTraverser::REMOVE_NODE;
    }

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Extbase\Utility\ExtensionUtility')
        )) {
            return true;
        }

        if (! $this->isName($staticMethodCall->name, 'registerPlugin')) {
            return true;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }
}
