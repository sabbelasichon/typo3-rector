<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\ExtensionKeyResolverTrait;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.2/Deprecation-108557-TCAOptionAllowedRecordTypesForPageTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v2\MigrateTcaOptionAllowedRecordTypesForPageTypesRector\MigrateTcaOptionAllowedRecordTypesForPageTypesRectorTest
 */
final class MigrateTcaOptionAllowedRecordTypesForPageTypesRector extends AbstractRector implements DocumentedRuleInterface
{
    use ExtensionKeyResolverTrait;

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

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
        ValueResolver $valueResolver,
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ComposerExtensionKeyResolver $composerExtensionKeyResolver,
        BetterStandardPrinter $betterStandardPrinter
    ) {
        $this->valueResolver = $valueResolver;
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->composerExtensionKeyResolver = $composerExtensionKeyResolver;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate TCA option allowedRecordTypes for Page Types', [new CodeSample(
            <<<'CODE_SAMPLE'
$dokTypeRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry::class);
$dokTypeRegistry->add(
    116,
    [
        'allowedTables' => '*',
    ],
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
// The configuration is moved to Configuration/TCA/Overrides/pages.php:
$GLOBALS['TCA']['pages']['types']['116']['allowedRecordTypes'] = ['*'];
CODE_SAMPLE
        )]);
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
    public function refactor(Node $node): ?int
    {
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        $args = $methodCall->getArgs();
        if (count($args) < 2) {
            return null;
        }

        $doktype = $this->valueResolver->getValue($args[0]->value);
        $config = $this->valueResolver->getValue($args[1]->value);

        // We only migrate if the allowedTables key is present
        if (! is_array($config) || ! isset($config['allowedTables'])) {
            return null;
        }

        $allowedTables = $config['allowedTables'];
        // Ensure we have an array for the new TCA option
        $allowedRecordTypes = is_string($allowedTables) ? array_map(
            'trim',
            explode(',', $allowedTables)
        ) : (array) $allowedTables;

        // Generate the PHP array string using the printer
        $allowedRecordTypesString = $this->betterStandardPrinter->print(
            $this->nodeFactory->createArray($allowedRecordTypes)
        );

        $content = sprintf(
            "\$GLOBALS['TCA']['pages']['types']['%s']['allowedRecordTypes'] = %s;",
            $doktype,
            $allowedRecordTypesString
        );

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/pages.php';

        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, PHP_EOL . $content . PHP_EOL);
        } else {
            $this->filesystem->write(
                $newConfigurationFile,
                <<<CODE
<?php

declare(strict_types=1);

{$content}

CODE
            );
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Core\DataHandling\PageDoktypeRegistry')
        )) {
            return true;
        }

        if (! $this->isName($methodCall->name, 'add')) {
            return true;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }
}
