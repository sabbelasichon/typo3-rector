<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PhpParser\Parser\RectorParser;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Error\SystemError;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\ExtensionKeyResolverTrait;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Throwable;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-96733-NewBackendModuleRegistrationAPI.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateBackendModuleRegistrationRector\MigrateBackendModuleRegistrationRectorTest
 */
final class MigrateBackendModuleRegistrationRector extends AbstractRector
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

    /**
     * @readonly
     */
    private RectorParser $rectorParser;

    /**
     * @readonly
     */
    private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator;

    /**
     * @readonly
     */
    private BetterStandardPrinter $betterStandardPrinter;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        ComposerExtensionKeyResolver $composerExtensionKeyResolver,
        RectorParser $rectorParser,
        NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        BetterStandardPrinter $betterStandardPrinter
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->composerExtensionKeyResolver = $composerExtensionKeyResolver;
        $this->rectorParser = $rectorParser;
        $this->nodeScopeAndMetadataDecorator = $nodeScopeAndMetadataDecorator;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate Backend Module Registration', [new CodeSample(
            <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'web',
    'example',
    'top',
    '',
    [
        'routeTarget' => MyExampleModuleController::class . '::handleRequest',
        'name' => 'web_example',
        'access' => 'admin',
        'workspaces' => 'online',
        'iconIdentifier' => 'module-example',
        'labels' => 'LLL:EXT:example/Resources/Private/Language/locallang_mod.xlf',
        'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
    ]
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
    'Extkey',
    'web',
    'example',
    'after:info',
    [
        MyExtbaseExampleModuleController::class => 'list, detail',
    ],
    [
        'access' => 'admin',
        'workspaces' => 'online',
        'iconIdentifier' => 'module-example',
        'labels' => 'LLL:EXT:extkey/Resources/Private/Language/locallang_mod.xlf',
    ]
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
// Configuration/Backend/Modules.php
return [
    'web_module' => [
        'parent' => 'web',
        'position' => ['before' => '*'],
        'access' => 'admin',
        'workspaces' => 'live',
        'path' => '/module/web/example',
        'iconIdentifier' => 'module-example',
        'navigationComponent' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement',
        'labels' => 'LLL:EXT:example/Resources/Private/Language/locallang_mod.xlf',
        'routes' => [
            '_default' => [
                'target' => MyExampleModuleController::class . '::handleRequest',
            ],
        ],
    ],
    'web_ExtkeyExample' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'admin',
        'workspaces' => 'live',
        'iconIdentifier' => 'module-example',
        'path' => '/module/web/ExtkeyExample',
        'labels' => 'LLL:EXT:extkey/Resources/Private/Language/locallang_mod.xlf',
        'extensionName' => 'Extkey',
        'controllerActions' => [
            MyExtbaseExampleModuleController::class => [
                'list',
                'detail'
            ],
        ],
    ],
];
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
        $staticMethodCall = $node->expr;

        if (! $staticMethodCall instanceof StaticCall) {
            return null;
        }

        if ($this->shouldSkip($staticMethodCall)) {
            return null;
        }

        if ($this->isName($staticMethodCall->name, 'addModule')) {
            $returnArray = $this->createArrayForAddModuleCall($staticMethodCall);
        } else {
            $returnArray = $this->createArrayForRegisterModuleCall($staticMethodCall);
        }

        $content = ArrayUtility::arrayExport($returnArray);

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/Backend/Modules.php';

        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $existingFileContent = $this->filesystem->read($newConfigurationFile);

            // This is very ugly but it works, see https://github.com/nikic/PHP-Parser/issues/1019
            $existingFileContent = <<<CODE
{$existingFileContent}
return {$content};

CODE;
            $existingFile = new File($newConfigurationFile, $existingFileContent);

            $parsingSystemError = $this->parseFileAndDecorateNodes($existingFile);
            if ($parsingSystemError instanceof SystemError) {
                // we cannot process this file as the parsing and type resolving itself went wrong
                return null;
            }

            $existingFile->changeHasChanged(\false);

            // This is very ugly but it works, see https://github.com/nikic/PHP-Parser/issues/1019
            $tempFile = new File('php://temp', $existingFileContent);
            $parsingSystemErrorTempFile = $this->parseFileAndDecorateNodes($tempFile);
            if ($parsingSystemErrorTempFile instanceof SystemError) {
                // we cannot process this file as the parsing and type resolving itself went wrong
                return null;
            }

            // Merge the arrays
            $this->mergeFiles($existingFile, $tempFile);

            // 3. Print to file
            $this->printFile($existingFile, $existingFile->getFilePath());
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

return {$content};

CODE);
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(StaticCall $staticMethodCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticMethodCall,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )
            && ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
                $staticMethodCall,
                new ObjectType('TYPO3\CMS\Extbase\Utility\ExtensionUtility')
            )
        ) {
            return true;
        }

        if (! $this->isName($staticMethodCall->name, 'addModule')
            && ! $this->isName($staticMethodCall->name, 'registerModule')
        ) {
            return true;
        }

        return ! $this->filesFinder->isExtTables($this->file->getFilePath());
    }

    /**
     * @return array<string, mixed>
     */
    private function createArrayForAddModuleCall(StaticCall $staticMethodCall): array
    {
        $main = $staticMethodCall->args[0]->value;
        $sub = $staticMethodCall->args[1]->value;
        $position = $staticMethodCall->args[2]->value;
        $path = $staticMethodCall->args[3]->value;
        $moduleConfiguration = $staticMethodCall->args[4]->value;

        $main = $this->valueResolver->getValue($main);
        $sub = $this->valueResolver->getValue($sub);
        $position = $this->valueResolver->getValue($position);
        $path = $this->valueResolver->getValue($path);
        $moduleArray = $this->valueResolver->getValue($moduleConfiguration);

        $moduleIdentifier = $main . '_' . $sub;

        $returnArray = [];
        $returnArray[$moduleIdentifier]['parent'] = $main;
        if ($position !== '' && $position !== 'bottom') {
            $returnArray[$moduleIdentifier]['position'] = $this->getPosition($position);
        }

        $returnArray[$moduleIdentifier]['access'] = $this->getAccess($moduleArray['access']);
        if (isset($moduleArray['workspaces'])) {
            $returnArray[$moduleIdentifier]['workspaces'] = $this->getWorkspaces($moduleArray['workspaces']);
        }

        $returnArray[$moduleIdentifier]['path'] = $this->getPath($path, $main, $sub);
        $returnArray[$moduleIdentifier]['iconIdentifier'] = $moduleArray['iconIdentifier'];
        if (isset($moduleArray['navigationComponentId'])) {
            $returnArray[$moduleIdentifier]['navigationComponent'] = $moduleArray['navigationComponentId'];
        }

        $returnArray[$moduleIdentifier]['labels'] = $moduleArray['labels'];
        $returnArray[$moduleIdentifier]['routes'] = [
            '_default' => [
                'target' => $moduleArray['routeTarget'],
            ],
        ];

        return $returnArray;
    }

    /**
     * @return array<string, mixed>
     */
    private function createArrayForRegisterModuleCall(StaticCall $staticMethodCall): array
    {
        $extensionName = $staticMethodCall->args[0]->value;
        $main = $staticMethodCall->args[1]->value;
        $sub = $staticMethodCall->args[2]->value;
        $position = $staticMethodCall->args[3]->value;
        $controllerActions = $staticMethodCall->args[4]->value;
        $moduleConfiguration = $staticMethodCall->args[5]->value;

        $extensionName = $this->valueResolver->getValue($extensionName);
        $main = $this->valueResolver->getValue($main);
        $sub = $this->valueResolver->getValue($sub);
        $position = $this->valueResolver->getValue($position);
        $controllerActions = $this->valueResolver->getValue($controllerActions);
        $moduleArray = $this->valueResolver->getValue($moduleConfiguration);

        $moduleIdentifier = $main . '_' . $extensionName . ucfirst(str_replace('_', '', $sub));

        $returnArray = [];
        $returnArray[$moduleIdentifier]['parent'] = $main;
        if ($position !== '' && $position !== 'bottom') {
            $returnArray[$moduleIdentifier]['position'] = $this->getPosition($position);
        }

        $returnArray[$moduleIdentifier]['access'] = $this->getAccess($moduleArray['access']);
        if (isset($moduleArray['workspaces'])) {
            $returnArray[$moduleIdentifier]['workspaces'] = $this->getWorkspaces($moduleArray['workspaces']);
        }

        $returnArray[$moduleIdentifier]['iconIdentifier'] = $moduleArray['iconIdentifier'];
        if (isset($moduleArray['navigationComponentId'])) {
            $returnArray[$moduleIdentifier]['navigationComponent'] = $moduleArray['navigationComponentId'];
        }

        $returnArray[$moduleIdentifier]['labels'] = $moduleArray['labels'];
        $returnArray[$moduleIdentifier]['extensionName'] = $extensionName;
        $returnArray[$moduleIdentifier]['controllerActions'] = $this->getControllerActions($controllerActions);

        return $returnArray;
    }

    /**
     * @return array<string, string|null>|string
     */
    private function getPosition(string $position)
    {
        $modules = ['dashboard', 'file', 'help', 'site', 'system', 'tools', 'user', 'web'];

        [$place, $moduleReference] = array_pad(ArrayUtility::trimExplode(':', $position, true), 2, null);
        if ($place === null) {
            $place = 'bottom';
        }

        //        if ($place === null || ($moduleReference !== null && !in_array($moduleReference, $modules, true))) {
        //            $place = 'bottom';
        //        }
        $moduleReference = $this->migrateModuleReference($moduleReference);
        switch (strtolower($place)) {
            case 'after':
                return [
                    'after' => $moduleReference,
                ];
            case 'before':
                return [
                    'before' => $moduleReference,
                ];
            case 'top':
                return 'top';
            case 'bottom':
            default:
                return 'bottom';
        }
    }

    private function migrateModuleReference(?string $moduleReference): ?string
    {
        if ($moduleReference === null) {
            return null;
        }

        switch ($moduleReference) {
            case 'FilelistList':
                return 'media_management';
            case 'AboutAbout':
                return 'about';
            case 'StyleguideStyleguide':
                return 'help_styleguide';
            case 'configuration':
                return 'site_configuration';
            case 'redirects':
                return 'site_redirects';
            case 'BeuserTxPermission':
                return 'permissions_pages';
            case 'BeuserTxBeuser':
                return 'backend_user_management';
            case 'txschedulerM1':
                return 'scheduler';
            case 'reports':
                return 'system_reports';
            case 'BelogLog':
                return 'system_BelogLog';
            case 'dbint':
                return 'system_dbint';
            case 'config':
                return 'system_config';
            case 'toolsmaintenance':
                return 'tools_toolsmaintenance';
            case 'toolssettings':
                return 'tools_toolssettings';
            case 'toolsupgrade':
                return 'tools_toolsupgrade';
            case 'toolsenvironment':
                return 'tools_toolsenvironment';
            case 'ExtensionmanagerExtensionmanager':
                return 'tools_ExtensionmanagerExtensionmanager';
            case 'setup':
                return 'user_setup';
            case 'layout':
                return 'web_layout';
            case 'ViewpageView':
                return 'page_preview';
            case 'list':
                return 'web_list';
            case 'FormFormbuilder':
                return 'web_FormFormbuilder';
            case 'WorkspacesWorkspaces':
                return 'workspaces_admin';
            case 'info':
                return 'web_info';
            case 'IndexedSearchIsearch':
                return 'web_IndexedSearchIsearch';
            case 'RecyclerRecycler':
                return 'recycler';
            case 'ts':
                return 'web_ts';
            case 'cshmanual':
            default:
                return 'bottom';
        }
    }

    private function getAccess(string $access): string
    {
        $accessEntries = explode(',', $access);
        $key = array_search('group', $accessEntries, true);
        if ($key !== false) {
            unset($accessEntries[$key]);
        }

        return implode(',', $accessEntries);
    }

    private function getWorkspaces(string $workspaces): string
    {
        switch ($workspaces) {
            case 'online':
                return 'live';
            case 'offline':
                return 'offline';
            default:
                return '*';
        }
    }

    private function getPath(?string $path, string $main, string $sub): string
    {
        // This is maybe a bug in Rector for resolving the value of null
        if ($path === 'null') {
            $path = null;
        }

        if ($path !== null && $path !== '' && $path !== '0') {
            return '/' . ltrim($path, '/');
        }

        $fullModuleSignature = $main . ($sub !== '' ? '_' . $sub : '');
        $path = str_replace('_', '/', $fullModuleSignature);
        return '/module/' . strtolower(trim($path, '/'));
    }

    /**
     * @param array<string, string> $controllerActions
     * @return array<string, array<int, string>>
     */
    private function getControllerActions(array $controllerActions): array
    {
        $returnArray = [];
        foreach ($controllerActions as $key => $controllerAction) {
            $returnArray[$key] = ArrayUtility::trimExplode(',', $controllerAction);
        }

        return $returnArray;
    }

    private function parseFileAndDecorateNodes(File $file): ?SystemError
    {
        try {
            $this->parseFileNodes($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (Throwable $throwable) {
            return new SystemError($throwable->getMessage(), $file->getFilePath(), $throwable->getLine());
        }

        return null;
    }

    private function mergeFiles(File $existingFile, File $newFile): void
    {
        $existingStmts = $existingFile->getNewStmts();

        /** @var FileWithoutNamespace $fileWithoutNamespace */
        $fileWithoutNamespace = $existingStmts[0];

        // This is very ugly but it works, see https://github.com/nikic/PHP-Parser/issues/1019
        unset($fileWithoutNamespace->stmts[1]);

        $existingArray = $this->getNodeArray($fileWithoutNamespace, 0);

        // --- new php array

        $newStmts = $newFile->getNewStmts();

        /** @var FileWithoutNamespace $fileWithoutNamespace2 */
        $fileWithoutNamespace2 = $newStmts[0];

        $newArray = $this->getNodeArray($fileWithoutNamespace2, 1);

        // Merge the two arrays
        $existingArray->items[] = $newArray->items[0];

        $existingFile->changeNewStmts($existingStmts);
    }

    private function getNodeArray(FileWithoutNamespace $fileWithoutNamespace, int $index): Array_
    {
        /** @var Return_ $return */
        $return = $fileWithoutNamespace->stmts[$index];

        /** @var Array_ $array */
        $array = $return->expr;

        return $array;
    }

    private function printFile(File $file, string $filePath): void
    {
        // only save to string first, no need to print to file when not needed
        $newContent = $this->betterStandardPrinter->printFormatPreserving(
            $file->getNewStmts(),
            $file->getOldStmts(),
            $file->getOldTokens()
        );

        $file->changeFileContent($newContent);

        $this->filesystem->write($filePath, $newContent);
    }

    private function parseFileNodes(File $file): void
    {
        // store tokens by original file content, so we don't have to print them right now
        $stmtsAndTokens = $this->rectorParser->parseFileContentToStmtsAndTokens($file->getOriginalFileContent());
        $oldStmts = $stmtsAndTokens->getStmts();
        $oldTokens = $stmtsAndTokens->getTokens();
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file->getFilePath(), $oldStmts);
        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }
}
