<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v4;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Enum\NodeGroup;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.4/Deprecation-105252-DataProviderContextGettersAndSetters.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v4\MigrateDataProviderContextGettersAndSettersRector\MigrateDataProviderContextGettersAndSettersRectorTest
 */
final class MigrateDataProviderContextGettersAndSettersRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
{
    /**
     * @var array<string, string>
     */
    private const METHOD_TO_PROPERTY_MAP = [
        'getPageId' => 'pageId',
        'setPageId' => 'pageId',
        'getTableName' => 'tableName',
        'setTableName' => 'tableName',
        'getFieldName' => 'fieldName',
        'setFieldName' => 'fieldName',
        'getData' => 'data',
        'setData' => 'data',
        'getPageTsConfig' => 'pageTsConfig',
        'setPageTsConfig' => 'pageTsConfig',
    ];

    private SimpleCallableNodeTraverser $simpleCallableNodeTraverser;

    public function __construct(SimpleCallableNodeTraverser $simpleCallableNodeTraverser)
    {
        $this->simpleCallableNodeTraverser = $simpleCallableNodeTraverser;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate DataProviderContext getters and setters', [new CodeSample(
            <<<'CODE_SAMPLE'
$dataProviderContext = GeneralUtility::makeInstance(DataProviderContext::class);
$dataProviderContext
    ->setPageId($pageId)
    ->setTableName($parameters['table'])
    ->setFieldName($parameters['field'])
    ->setData($parameters['row'])
    ->setPageTsConfig($pageTsConfig);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$dataProviderContext = new DataProviderContext(
    pageId: $pageId,
    tableName: $parameters['table'],
    fieldName: $parameters['field'],
    data: $parameters['row'],
    pageTsConfig: $pageTsConfig,
);
CODE_SAMPLE
        ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$pageId = $dataProviderContext->getPageId();
$tableName = $dataProviderContext->getTableName();
$fieldName = $dataProviderContext->getFieldName();
$data = $dataProviderContext->getData();
$pageTsConfig = $dataProviderContext->getPageTsConfig();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$pageId = $dataProviderContext->pageId;
$tableName = $dataProviderContext->tableName;
$fieldName = $dataProviderContext->fieldName;
$data = $dataProviderContext->data;
$pageTsConfig = $dataProviderContext->pageTsConfig;
CODE_SAMPLE
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$dataProviderContext->setPageId(1);
$dataProviderContext->setTableName('table');
$dataProviderContext->setFieldName('field');
$dataProviderContext->setData([]);
$dataProviderContext->setPageTsConfig([]);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$dataProviderContext->pageId = 1;
$dataProviderContext->tableName = 'table';
$dataProviderContext->fieldName = 'field';
$dataProviderContext->data = [];
$dataProviderContext->pageTsConfig = [];
CODE_SAMPLE
            )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        $stmtsAware = NodeGroup::STMTS_AWARE;
        return [...$stmtsAware, MethodCall::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_80;
    }

    /**
     * @param StmtsAware|MethodCall $node
     * @return null|Node
     */
    public function refactor(Node $node)
    {
        if ($node instanceof MethodCall) {
            if ($this->shouldSkip($node)) {
                return null;
            }

            $methodName = $this->getName($node->name);
            if (! isset(self::METHOD_TO_PROPERTY_MAP[$methodName])) {
                return null;
            }

            $propertyName = self::METHOD_TO_PROPERTY_MAP[$methodName];
            $propertyFetch = new PropertyFetch($node->var, $propertyName);

            if (str_starts_with($methodName, 'get')) {
                return $propertyFetch;
            }

            if (str_starts_with($methodName, 'set')) {
                if (! isset($node->args[0])) {
                    return null;
                }

                $value = $node->args[0]->value;

                return new Assign($propertyFetch, $value);
            }

            return null;
        }

        if ($node->stmts === null) {
            return null;
        }

        $dataProviderContextStaticCall = null;
        $dataProviderContextStaticCallVariable = null;
        $dataProviderContextStaticCallIndex = null;
        /** @var MethodCall[] $methodCalls */
        $methodCalls = [];
        $methodCallsVariable = null;
        /** @var array<int, int> $methodCalls */
        $methodCallsIndexes = [];

        foreach ($node->stmts as $index => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if ($stmt->expr instanceof Assign) {
                $assign = $stmt->expr;
                if ($assign->expr instanceof StaticCall) {
                    $staticCall = $assign->expr;
                    if ($this->isName($staticCall->class, 'TYPO3\CMS\Core\Utility\GeneralUtility')
                        && $this->isName($staticCall->name, 'makeInstance')
                        && count($staticCall->args) === 1
                    ) {
                        $firstArgValue = $staticCall->args[0]->value;
                        if (! $firstArgValue instanceof ClassConstFetch
                            || ! $this->isName(
                                $firstArgValue->class,
                                'TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext'
                            )
                        ) {
                            continue;
                        }

                        $dataProviderContextStaticCall = $assign;
                        $dataProviderContextStaticCallIndex = $index;
                        if ($assign->var instanceof Variable) {
                            $dataProviderContextStaticCallVariable = $assign->var->name;
                        }

                        continue;
                    }
                }
            }

            if (! $dataProviderContextStaticCall instanceof Assign) {
                // abort if we haven't found the initialization of DataProviderContext yet
                continue;
            }

            if (! $stmt->expr instanceof MethodCall) {
                continue;
            }

            $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmt->expr, static function (Node $n) use (
                &$methodCalls,
                &$methodCallsVariable
            ) {
                if ($n instanceof MethodCall) {
                    $methodCalls[] = $n;
                    return $n;
                }

                if ($n instanceof Variable) {
                    $methodCallsVariable = $n->name;
                    return NodeVisitor::DONT_TRAVERSE_CHILDREN;
                }

                if ($n instanceof Arg) {
                    return NodeVisitor::DONT_TRAVERSE_CHILDREN;
                }

                return null;
            });

            if ($dataProviderContextStaticCallVariable !== null
                && $methodCallsVariable !== null
                && $dataProviderContextStaticCallVariable === $methodCallsVariable
            ) {
                $methodCallsIndexes[] = $index;
            }
        }

        if ($dataProviderContextStaticCallVariable !== $methodCallsVariable) {
            return null;
        }

        foreach ($methodCallsIndexes as $methodCallsIndex) {
            unset($node->stmts[$methodCallsIndex]);
        }

        // create new method
        $argumentMap = [
            'setPageId' => 'pageId',
            'setTableName' => 'tableName',
            'setFieldName' => 'fieldName',
            'setData' => 'data',
            'setPageTsConfig' => 'pageTsConfig',
        ];
        $setterArgsCollected = [];
        foreach ($methodCalls as $methodCall) {
            /** @var MethodCall $methodCall */
            $methodName = $this->getName($methodCall->name);
            if ($methodName === null || count($methodCall->args) !== 1) {
                continue;
            }

            if (isset($argumentMap[$methodName])) {
                $setterArgsCollected[$methodName] = $methodCall->args[0];
            }
        }

        if ($setterArgsCollected === []) {
            return null;
        }

        // Create named arguments for the constructor
        $constructorArgs = [];
        foreach ($argumentMap as $setterName => $constructorArgName) {
            if (isset($setterArgsCollected[$setterName])) {
                /** @var Arg $argNode */
                $argNode = $setterArgsCollected[$setterName];
                $constructorArgs[] = new Arg(
                    $argNode->value,
                    false,
                    false,
                    [],
                    new Identifier($constructorArgName)
                );
            }
        }

        $constructorArgs = $this->sortArgs($constructorArgs);

        /** @var Expression $expression */
        $expression = $node->stmts[$dataProviderContextStaticCallIndex];

        /** @var Assign $assign */
        $assign = $expression->expr;
        $assign->expr = new New_(
            new FullyQualified('TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext'),
            $constructorArgs
        );

        return $node;
    }

    /**
     * @param array<int, Arg> $args
     * @return array<int, Arg>
     */
    private function sortArgs(array $args): array
    {
        $desiredOrder = ['pageId', 'tableName', 'fieldName', 'data', 'pageTsConfig'];
        usort($args, static function (Arg $a, Arg $b) use ($desiredOrder) {
            $aName = ($a->name instanceof Identifier) ? $a->name->toString() : '';
            $bName = ($b->name instanceof Identifier) ? $b->name->toString() : '';

            $aPos = array_search($aName, $desiredOrder, true);
            $bPos = array_search($bName, $desiredOrder, true);

            if ($aPos === false) {
                return 1;
            }

            if ($bPos === false) {
                return -1;
            }

            return $aPos <=> $bPos;
        });
        return $args;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        return ! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Backend\View\BackendLayout\DataProviderContext')
        );
    }
}
