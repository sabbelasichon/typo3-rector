<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerPsr4Resolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105076-PluginContentElementAndPluginSubTypes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigratePluginContentElementAndPluginSubtypesRector\MigratePluginContentElementAndPluginSubtypesRectorTest
 */
final class MigratePluginContentElementAndPluginSubtypesRector extends AbstractRector
{
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

    /**
     * @readonly
     */
    private ComposerPsr4Resolver $composerPsr4Resolver;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        ComposerPsr4Resolver $composerPsr4Resolver
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->composerPsr4Resolver = $composerPsr4Resolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate plugin content element and plugin subtypes (list_type)', [new CodeSample(
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin([], 'list_type', 'extension_key');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'list_type');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin([], 'CType', 'extension_key');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('ExtensionName', 'PluginName', [], [], 'CType');
CODE_SAMPLE
        )]);
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $type = 'list_type';
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            if (isset($node->args[1])) {
                $type = $this->valueResolver->getValue($node->args[1]->value);
            }

            if ($type === 'list_type') {
                $node->args[1] = new Arg(new ClassConstFetch(new FullyQualified(
                    'TYPO3\CMS\Extbase\Utility\ExtensionUtility'
                ), new Identifier('PLUGIN_TYPE_CONTENT_ELEMENT')));
            }
        } else {
            if (isset($node->args[4])) {
                $type = $this->valueResolver->getValue($node->args[4]->value);
            } elseif (! isset($node->args[3])) {
                $node->args[3] = new Arg(new Array_());
            }

            if ($type === 'list_type') {
                $node->args[4] = new Arg(new ClassConstFetch(new FullyQualified(
                    'TYPO3\CMS\Extbase\Utility\ExtensionUtility'
                ), new Identifier('PLUGIN_TYPE_CONTENT_ELEMENT')));
            }
        }

        $psr4 = $this->composerPsr4Resolver->resolve($this->file);
        if ($psr4 !== null) {
            $filePath = $this->file->getFilePath();
            $directoryName = $this->filesFinder->isInTCAOverridesFolder($filePath)
                ? dirname($filePath, 4)
                : dirname($filePath);

            $namespaceParts = explode('\\', $psr4);
            $vendor = $namespaceParts[0];
            $lowerCasedVendor = mb_strtolower($vendor);
            $extensionName = $namespaceParts[1];

            $migrationFile = $directoryName . '/Classes/Updates/' . $vendor . $extensionName . 'CTypeMigration.php';
            if (! $this->filesystem->fileExists($migrationFile)) {
                $content = <<<CODE
<?php

declare(strict_types=1);

namespace {$psr4}Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('{$lowerCasedVendor}{$extensionName}CTypeMigration')]
final class {$vendor}{$extensionName}CTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "{$vendor} {$extensionName}" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "{$vendor} {$extensionName}" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            // TODO: Add this mapping yourself!
        ];
    }
}

CODE;
                $this->filesystem->write($migrationFile, $content);
            }
        }

        return $node;
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        ) && $this->isName($staticCall->name, 'addPlugin')
        ) {
            if (isset($staticCall->args[1])) {
                $type = $staticCall->args[1]->value;

                if ($type instanceof String_ && $type->value === 'CType') {
                    return true;
                }

                return $type instanceof ClassConstFetch
                    && $this->isName($type->class, 'TYPO3\CMS\Extbase\Utility\ExtensionUtility')
                    && $this->isName($type->name, 'PLUGIN_TYPE_CONTENT_ELEMENT');
            }

            return false;
        }

        if ($this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Extbase\Utility\ExtensionUtility')
        ) && $this->isName($staticCall->name, 'configurePlugin')
        ) {
            if (isset($staticCall->args[4])) {
                $pluginType = $staticCall->args[4]->value;

                if ($pluginType instanceof String_ && $pluginType->value === 'CType') {
                    return true;
                }

                return $pluginType instanceof ClassConstFetch
                    && $this->isName($pluginType->class, 'TYPO3\CMS\Extbase\Utility\ExtensionUtility')
                    && $this->isName($pluginType->name, 'PLUGIN_TYPE_CONTENT_ELEMENT');
            }

            return false;
        }

        return true;
    }
}
