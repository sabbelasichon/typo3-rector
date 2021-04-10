<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-79580-MethodsInDataHandlerRelatedToPageDeleteAccess.html
 */
final class DataHandlerRmCommaRector extends AbstractRector
{
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
        if (! $this->isName($node->name, 'rmComma')) {
            return null;
        }
        /** @var Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);
        return $this->nodeFactory->createFuncCall('rtrim', [$firstArgument, $this->nodeFactory->createArg(',')]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate the method DataHandler::rmComma() to use rtrim()', [
            new CodeSample(<<<'CODE_SAMPLE'
$inList = '1,2,3,';
$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
$inList = $dataHandler->rmComma(trim($inList));
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$inList = '1,2,3,';
$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
$inList = rtrim(trim($inList), ',');
CODE_SAMPLE
),
        ]);
    }
}
