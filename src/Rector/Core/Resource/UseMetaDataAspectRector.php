<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Resource;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Resource\File;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-85895-DeprecateFile_getMetaData.html
 */
final class UseMetaDataAspectRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, File::class)) {
            return null;
        }

        if (! $this->isName($node->name, '_getMetaData')) {
            return null;
        }

        return $this->createMethodCall($this->createMethodCall($node->var, 'getMetaData'), 'get');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use $fileObject->getMetaData()->get() instead of $fileObject->_getMetaData()', [
            new CodeSample(
                <<<'PHP'
$fileObject = new File();
$fileObject->_getMetaData();
PHP
                ,
                <<<'PHP'
$fileObject = new File();
$fileObject->getMetaData()->get();
PHP
            ),
        ]);
    }
}
