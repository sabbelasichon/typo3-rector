<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\DataHandling;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-79580-MethodsInDataHandlerRelatedToPageDeleteAccess.html
 */
final class DataHandlerRmCommaRector extends AbstractRector
{
    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, DataHandler::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'rmComma')) {
            return null;
        }

        /** @var Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);

        return $this->createFuncCall('rtrim', [$firstArgument, $this->createArg(',')]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrate the method DataHandler::rmComma() to use rtrim()', [
            new CodeSample(
                <<<'PHP'
$inList = '1,2,3,';
$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
$inList = $dataHandler->rmComma(trim($inList));
PHP
                ,
                <<<'PHP'
$inList = '1,2,3,';
$dataHandler = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
$inList = rtrim(trim($inList), ',');
PHP
            ),
        ]);
    }
}
