<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\DataHandling\DataHandler;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-80513-DataHandlerVariousMethodsAndMethodArguments.html
 */
final class DataHandlerVariousMethodsAndMethodArgumentsRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, DataHandler::class)) {
            return null;
        }

        if ($this->isName($node->name, 'destPathFromUploadFolder')) {
            ///** @var Arg[] $args */
            //$args = $node->args;
            //$firstArgument = array_shift($args);
            //$dest = PATH_SITE . $firstArgument
        }

        if ($this->isName($node->name, 'extFileFunctions') && 4 === count($node->args)) {
            $this->removeNode($node->args[3]);
            return $node;
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Remove CharsetConvertParameters',
            [
                new CodeSample(<<<'PHP'
$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$dest = $dataHandler->destPathFromUploadFolder('uploadFolder');
$dataHandler->extFileFunctions('table', 'field', 'theField', 'deleteAll');
PHP
                    , <<<'PHP'
$dataHandler = GeneralUtility::makeInstance(DataHandler::class);
$dest = PATH_SITE . 'uploadFolder';
$dataHandler->extFileFunctions('table', 'field', 'theField');
PHP
                ),
            ]
        );
    }
}
