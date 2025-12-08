<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\PhpParser\Printer\PrettyTypo3Printer;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107287-FileCollectionRegistryAddTypeToTCA.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateFileCollectionRegistryAddTypeToTCARector\MigrateFileCollectionRegistryAddTypeToTCARectorTest
 */
final class MigrateFileCollectionRegistryAddTypeToTCARector extends AbstractRector implements DocumentedRuleInterface
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
    private PrettyTypo3Printer $prettyTypo3Printer;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        PrettyTypo3Printer $prettyTypo3Printer
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->prettyTypo3Printer = $prettyTypo3Printer;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `FileCollectionRegistry->addTypeToTCA()`', [new CodeSample(
            <<<'CODE_SAMPLE'
$fileCollectionRegistry = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry::class);
$fileCollectionRegistry->addTypeToTCA(
    'mytype',
    'My Collection Type',
    'description,my_field',
    ['my_field' => ['config' => ['type' => 'input']]]
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
// In Configuration/TCA/Overrides/sys_file_collection.php
$GLOBALS['TCA']['sys_file_collection']['types']['mytype'] = [
    'showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, title, --palette--;;1, type, description, my_field',
];

$GLOBALS['TCA']['sys_file_collection']['columns']['type']['config']['items'][] = [
    'label' => 'My Collection Type',
    'value' => 'mytype',
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
        $methodCall = $node->expr;
        if (! $methodCall instanceof MethodCall) {
            return null;
        }

        if ($this->shouldSkip($methodCall)) {
            return null;
        }

        $args = $methodCall->getArgs();
        if (count($args) < 3) {
            return null;
        }

        $typeExpr = $args[0]->value;
        $labelExpr = $args[1]->value;
        $fieldsExpr = $args[2]->value;
        $additionalColumnsExpr = isset($args[3]) ? $args[3]->value : null;

        $newNodes = [];

        // Create types assignment
        $newNodes[] = $this->createTypesAssignment($typeExpr, $fieldsExpr);

        $newNodes[] = new Nop();

        // Create items assignment
        $newNodes[] = $this->createItemsAssignment($typeExpr, $labelExpr);

        $newNodes[] = new Nop();

        // Create addTCAcolumns call if needed
        if ($additionalColumnsExpr !== null) {
            $newNodes[] = new Expression($this->createAddTCAColumnsCall($additionalColumnsExpr));
        }

        $content = $this->prettyTypo3Printer->prettyPrint($newNodes);

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/sys_file_collection.php';

        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function createTypesAssignment(Expr $typeExpr, Expr $fieldsExpr): Expression
    {
        $typesArrayFetch = $this->createGlobalsPath(['TCA', 'sys_file_collection', 'types']);
        $targetArrayFetch = new ArrayDimFetch($typesArrayFetch, $typeExpr);

        $baseString = 'sys_language_uid, l10n_parent, l10n_diffsource, title, --palette--;;1, type, ';

        if ($fieldsExpr instanceof String_) {
            $showItemValue = new String_($baseString . $fieldsExpr->value);
        } else {
            $showItemValue = new Concat(new String_($baseString), $fieldsExpr);
        }

        $arrayItem = new ArrayItem($showItemValue, new String_('showitem'));
        $assignedArray = new Array_([$arrayItem]);

        return new Expression(new Assign($targetArrayFetch, $assignedArray));
    }

    private function createItemsAssignment(Expr $typeExpr, Expr $labelExpr): Expression
    {
        $itemsArrayFetch = $this->createGlobalsPath(
            ['TCA', 'sys_file_collection', 'columns', 'type', 'config', 'items']
        );
        $targetArrayFetch = new ArrayDimFetch($itemsArrayFetch);

        $arrayItems = [
            new ArrayItem($labelExpr, new String_('label')),
            new ArrayItem($typeExpr, new String_('value')),
        ];

        return new Expression(new Assign($targetArrayFetch, new Array_($arrayItems)));
    }

    private function createAddTCAColumnsCall(Expr $columnsExpr): StaticCall
    {
        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Utility\ExtensionManagementUtility',
            'addTCAcolumns',
            [new String_('sys_file_collection'), $columnsExpr]
        );
    }

    /**
     * @param string[] $keys
     */
    private function createGlobalsPath(array $keys): ArrayDimFetch
    {
        $current = new Variable('GLOBALS');
        foreach ($keys as $key) {
            $current = new ArrayDimFetch($current, new String_($key));
        }

        return $current;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Core\Resource\Collection\FileCollectionRegistry')
        )) {
            return true;
        }

        if (! $this->isName($methodCall->name, 'addTypeToTCA')) {
            return true;
        }

        return ! $this->filesFinder->isExtLocalConf($this->file->getFilePath());
    }
}
