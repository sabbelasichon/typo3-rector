<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Nop;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-83121-LoggingMethodDataHandler-newlog2.html
 */
final class UseLogMethodInsteadOfNewLog2Rector extends AbstractRector
{
    /**
     * @var string
     */
    private const PID = 'pid';

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
            new ObjectType(DataHandler::class)
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'newlog2')) {
            return null;
        }

        if (! isset($node->args[3]) || (isset($node->args[3]) && $this->valueResolver->isNull($node->args[3]->value))) {
            $propArrayNode = new Assign(new Variable('propArr'), $this->nodeFactory->createMethodCall(
                $node->var,
                'getRecordProperties',
                [$node->args[1], $node->args[2]]
            ));
            $this->addNodeBeforeNode($propArrayNode, $node);

            $pidNode = new Expression(new Assign(new Variable(self::PID), new ArrayDimFetch(new Variable(
                'propArr'
            ), new String_(self::PID))));
            $this->addNodeBeforeNode($pidNode, $node);

            $this->addNodeBeforeNode(new Nop(), $node);
        }

        $node->name = new Identifier('log');

        $node->args = $this->nodeFactory->createArgs([
            $node->args[1],
            $node->args[2],
            new LNumber(0),
            new LNumber(0),
            $node->args[4] ?? new LNumber(0),
            $node->args[0],
            new LNumber(-1),
            new Array_(),
            $this->nodeFactory->createMethodCall($node->var, 'eventPid', [
                $node->args[1],
                $node->args[2],
                isset($node->args[3]) && ! $this->valueResolver->isNull(
                    $node->args[3]->value
                ) ? $node->args[3] : new Variable(self::PID),
            ]),
        ]);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use log method instead of newlog2 from class DataHandler', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$logEntryUid1 = $dataHandler->newlog2('Foo', 'pages', 1, null, 0);
$logEntryUid2 = $dataHandler->newlog2('Foo', 'tt_content', 1, 2, 1);
$logEntryUid3 = $dataHandler->newlog2('Foo', 'tt_content', 1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$propArr = $dataHandler->getRecordProperties('pages', 1);
$pid = $propArr['pid'];

$logEntryUid1 = $dataHandler->log('pages', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('pages', 1, $pid));
$logEntryUid2 = $dataHandler->log('tt_content', 1, 0, 0, 1, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, 2));
$propArr = $dataHandler->getRecordProperties('tt_content', 1);
$pid = $propArr['pid'];

$logEntryUid3 = $dataHandler->log('tt_content', 1, 0, 0, 0, 'Foo', -1, [], $dataHandler->eventPid('tt_content', 1, $pid));
CODE_SAMPLE
            ),
        ]);
    }
}
