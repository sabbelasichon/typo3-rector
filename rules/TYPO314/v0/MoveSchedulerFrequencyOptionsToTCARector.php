<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;
use Ssch\TYPO3Rector\Contract\FilesystemInterface;
use Ssch\TYPO3Rector\Filesystem\FilesFinder;
use Ssch\TYPO3Rector\Helper\ExtensionKeyResolverTrait;
use Ssch\TYPO3Rector\PhpParser\Printer\PrettyTypo3Printer;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107488-SchedulerFrequencyOptionsMovedToTCA.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveSchedulerFrequencyOptionsToTCARector\MoveSchedulerFrequencyOptionsToTCARectorTest
 */
final class MoveSchedulerFrequencyOptionsToTCARector extends AbstractRector implements DocumentedRuleInterface
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
    private PrettyTypo3Printer $printer;

    public function __construct(
        FilesFinder $filesFinder,
        FilesystemInterface $filesystem,
        ValueResolver $valueResolver,
        ComposerExtensionKeyResolver $composerExtensionKeyResolver,
        PrettyTypo3Printer $prettyTypo3Printer
    ) {
        $this->filesFinder = $filesFinder;
        $this->filesystem = $filesystem;
        $this->valueResolver = $valueResolver;
        $this->composerExtensionKeyResolver = $composerExtensionKeyResolver;
        $this->printer = $prettyTypo3Printer;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move Scheduler frequency options to TCA', [new CodeSample(
            <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['frequencyOptions']['0 2 * * *'] = 'LLL:EXT:my_extension/Resources/Private/Language/locallang.xlf:daily_2am';
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
// Added under Configuration/TCA/Overrides/tx_scheduler_task.php
$GLOBALS['TCA']['tx_scheduler_task']['columns']['execution_details']['config']['overrideFieldTca']['frequency']['config']['valuePicker']['items'][] = [
    'value' => '0 2 * * *',
    'label' => 'LLL:EXT:my_extension/Resources/Private/Language/locallang.xlf:daily_2am',
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Assign $assign */
        $assign = $node->expr;

        /** @var ArrayDimFetch $oldAssignVar */
        $oldAssignVar = $assign->var;

        if (! $oldAssignVar->dim instanceof Expr) {
            return null;
        }

        $cronValueNode = $oldAssignVar->dim;
        $labelValueNode = $assign->expr;

        $newArray = new Array_([
            new ArrayItem($cronValueNode, new String_('value')),
            new ArrayItem($labelValueNode, new String_('label')),
        ]);

        $newAssignTarget = $this->createNewAssignmentTarget();
        $arrayPush = new ArrayDimFetch($newAssignTarget, null);

        $newAssign = new Assign($arrayPush, $newArray);

        $this->writeStatementToFile(new Expression($newAssign));

        return NodeVisitor::REMOVE_NODE;
    }

    private function shouldSkip(Expression $node): bool
    {
        if (! $node->expr instanceof Assign) {
            return true;
        }

        $assignVar = $node->expr->var;

        if (! $assignVar instanceof ArrayDimFetch) {
            return true;
        }

        // Check for 'frequencyOptions'
        $freqOptionsDimFetch = $assignVar->var;
        if (! $freqOptionsDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($freqOptionsDimFetch->dim, 'frequencyOptions')
        ) {
            return true;
        }

        // Check for 'scheduler'
        $schedulerDimFetch = $freqOptionsDimFetch->var;
        if (! $schedulerDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($schedulerDimFetch->dim, 'scheduler')
        ) {
            return true;
        }

        // Check for 'SC_OPTIONS'
        $scOptionsDimFetch = $schedulerDimFetch->var;
        if (! $scOptionsDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($scOptionsDimFetch->dim, 'SC_OPTIONS')
        ) {
            return true;
        }

        // Check for 'TYPO3_CONF_VARS'
        $typo3ConfVarsDimFetch = $scOptionsDimFetch->var;
        if (! $typo3ConfVarsDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($typo3ConfVarsDimFetch->dim, 'TYPO3_CONF_VARS')
        ) {
            return true;
        }

        // Check for '$GLOBALS'
        $globalsVar = $typo3ConfVarsDimFetch->var;
        if (! $globalsVar instanceof Variable || ! $this->isName($globalsVar, 'GLOBALS')) {
            return true;
        }

        return ! $this->filesFinder->isExtLocalConf($this->file->getFilePath());
    }

    private function createNewAssignmentTarget(): ArrayDimFetch
    {
        $globals = new Variable('GLOBALS');
        $tca = new ArrayDimFetch($globals, new String_('TCA'));
        $task = new ArrayDimFetch($tca, new String_('tx_scheduler_task'));
        $columns = new ArrayDimFetch($task, new String_('columns'));
        $executionDetails = new ArrayDimFetch($columns, new String_('execution_details'));
        $config = new ArrayDimFetch($executionDetails, new String_('config'));
        $override = new ArrayDimFetch($config, new String_('overrideFieldTca'));
        $frequency = new ArrayDimFetch($override, new String_('frequency'));
        $frequencyConfig = new ArrayDimFetch($frequency, new String_('config'));
        $valuePicker = new ArrayDimFetch($frequencyConfig, new String_('valuePicker'));
        return new ArrayDimFetch($valuePicker, new String_('items'));
    }

    private function writeStatementToFile(Expression $expression): void
    {
        $content = $this->printer->prettyPrint([$expression]);

        $directoryName = dirname($this->file->getFilePath());
        $newConfigurationFile = $directoryName . '/Configuration/TCA/Overrides/tx_scheduler_task.php';
        if ($this->filesystem->fileExists($newConfigurationFile)) {
            $this->filesystem->appendToFile($newConfigurationFile, PHP_EOL . $content . PHP_EOL);
        } else {
            $this->filesystem->write($newConfigurationFile, <<<CODE
<?php

{$content}

CODE
            );
        }
    }
}
