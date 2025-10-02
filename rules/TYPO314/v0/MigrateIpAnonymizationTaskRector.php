<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
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
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-107562-IpAnonymizationTaskConfigurationViaGlobals.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateIpAnonymizationTaskRector\MigrateIpAnonymizationTaskRectorTest
 */
final class MigrateIpAnonymizationTaskRector extends AbstractRector implements DocumentedRuleInterface
{
    use ExtensionKeyResolverTrait;

    /**
     * @var string
     */
    private const IP_ANONYMIZATION_TASK = 'TYPO3\CMS\Scheduler\Task\IpAnonymizationTask';

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
        return new RuleDefinition(
            'Migrates the IpAnonymizationTask configuration from $GLOBALS[\'TYPO3_CONF_VARS\'] to $GLOBALS[\'TCA\'].',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]['options']['tables'] = [
    'my_table' => [
        'dateField' => 'tstamp',
        'ipField' => 'private_ip',
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
// Added under Configuration/TCA/Overrides/tx_scheduler_task.php
if (isset($GLOBALS['TCA']['tx_scheduler_task'])) {
    $GLOBALS['TCA']['tx_scheduler_task']['types'][\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]['taskOptions']['tables'] = [
        'my_table' => [
            'dateField' => 'tstamp',
            'ipField' => 'private_ip',
        ],
    ];
}
CODE_SAMPLE
            )]
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Assign $assign */
        $assign = $node->expr;

        /** @var ArrayDimFetch $assignVar */
        $assignVar = $assign->var;

        /** @var ArrayDimFetch $optionsDimFetch */
        $optionsDimFetch = $assignVar->var;

        /** @var ArrayDimFetch $taskClassDimFetch */
        $taskClassDimFetch = $optionsDimFetch->var;

        /** @var ClassConstFetch|String_ $classIdentifier */
        $classIdentifier = $taskClassDimFetch->dim;

        // Create the condition for the if statement: isset($GLOBALS['TCA']['tx_scheduler_task'])
        $issetVar = new ArrayDimFetch(
            new ArrayDimFetch(new Variable('GLOBALS'), new String_('TCA')),
            new String_('tx_scheduler_task')
        );
        $issetNode = new Isset_([$issetVar]);

        // Create the new assignment expression
        $newAssignVar = $this->createNewAssignmentTarget($classIdentifier);
        $newAssign = new Assign($newAssignVar, $assign->expr);

        // Wrap the assignment in an if statement
        $ifStatement = new If_($issetNode, [
            'stmts' => [new Expression($newAssign)],
        ]);

        $this->writeStatementToFile($ifStatement);

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

        if (! $assignVar->dim instanceof Expr) {
            return true;
        }

        // Check for 'tables'
        if (! $this->valueResolver->isValue($assignVar->dim, 'tables')) {
            return true;
        }

        // Check for 'options'
        $optionsDimFetch = $assignVar->var;
        if (! $optionsDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($optionsDimFetch->dim, 'options')
        ) {
            return true;
        }

        // Check for IpAnonymizationTask::class
        $taskClassDimFetch = $optionsDimFetch->var;
        if (! $taskClassDimFetch instanceof ArrayDimFetch) {
            return true;
        }

        $classIdentifier = $taskClassDimFetch->dim;

        $isClassConst = $classIdentifier instanceof ClassConstFetch
            && $this->isName($classIdentifier->class, self::IP_ANONYMIZATION_TASK)
            && $this->isName($classIdentifier->name, 'class');

        $isString = $classIdentifier instanceof String_ && $this->valueResolver->isValue($classIdentifier, self::IP_ANONYMIZATION_TASK);

        if (! $isClassConst && ! $isString) {
            return true;
        }

        // Check for 'tasks'
        $tasksDimFetch = $taskClassDimFetch->var;
        if (! $tasksDimFetch instanceof ArrayDimFetch
            || ! $this->valueResolver->isValue($tasksDimFetch->dim, 'tasks')
        ) {
            return true;
        }

        // Check for 'scheduler'
        $schedulerDimFetch = $tasksDimFetch->var;
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

    private function createNewAssignmentTarget(Expr $classConstFetch): ArrayDimFetch
    {
        // $GLOBALS['TCA']
        $tcaFetch = new ArrayDimFetch(new Variable('GLOBALS'), new String_('TCA'));
        // ['tx_scheduler_task']
        $taskFetch = new ArrayDimFetch($tcaFetch, new String_('tx_scheduler_task'));
        // ['types']
        $typesFetch = new ArrayDimFetch($taskFetch, new String_('types'));
        // [\TYPO3\CMS\Scheduler\Task\IpAnonymizationTask::class]
        $classFetch = new ArrayDimFetch($typesFetch, $classConstFetch);
        // ['taskOptions']
        $taskOptionsFetch = new ArrayDimFetch($classFetch, new String_('taskOptions'));
        // ['tables']
        return new ArrayDimFetch($taskOptionsFetch, new String_('tables'));
    }

    private function writeStatementToFile(If_ $ifStatement): void
    {
        $content = $this->printer->prettyPrint([$ifStatement]);

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
