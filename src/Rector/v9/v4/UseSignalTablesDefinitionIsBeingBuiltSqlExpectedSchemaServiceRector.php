<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeAnalyzer\ClassConstAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
use TYPO3\CMS\Install\Service\SqlExpectedSchemaService;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85462-SignalTablesDefinitionIsBeingBuilt.html
 */
final class UseSignalTablesDefinitionIsBeingBuiltSqlExpectedSchemaServiceRector extends AbstractRector
{
    /**
     * @var ClassConstAnalyzer
     */
    private $classConstAnalyzer;

    public function __construct(ClassConstAnalyzer $classConstAnalyzer)
    {
        $this->classConstAnalyzer = $classConstAnalyzer;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType(Dispatcher::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'connect')) {
            return null;
        }

        if (! $this->classConstAnalyzer->isClassConstReference($node->args[0]->value, InstallUtility::class)) {
            return null;
        }

        if (! $this->valueResolver->isValue($node->args[1]->value, 'tablesDefinitionIsBeingBuilt')) {
            return null;
        }

        $node->args[0]->value = $this->nodeFactory->createClassConstReference(SqlExpectedSchemaService::class);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use the signal tablesDefinitionIsBeingBuilt of class SqlExpectedSchemaService', [
            new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Extensionmanager\Utility\InstallUtility;
$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
$signalSlotDispatcher->connect(
        InstallUtility::class,
        'tablesDefinitionIsBeingBuilt',
        \stdClass::class,
        'foo'
    );
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\CMS\Install\Service\SqlExpectedSchemaService;
$signalSlotDispatcher = GeneralUtility::makeInstance(Dispatcher::class);
    $signalSlotDispatcher->connect(
        SqlExpectedSchemaService::class,
        'tablesDefinitionIsBeingBuilt',
        \stdClass::class,
        'foo'
    );
CODE_SAMPLE
            ),
        ]);
    }
}
