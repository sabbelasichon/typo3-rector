<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\AbstractController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Breaking-87627-RemovePropertyExtensionNameOfAbstractController.html
 */
final class RemovePropertyExtensionNameRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isObjectType($node->var, AbstractController::class)) {
            return null;
        }

        if (!$this->isName($node, 'extensionName')) {
            return null;
        }

        return $this->createMethodCall($this->createPropertyFetch($node->var, 'request'), 'getControllerExtensionName');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use method getControllerExtensionName from $request property instead of removed property $extensionName', [
            new CodeSample(
                <<<'PHP'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->extensionName === 'whatever') {

        }

        $extensionName = $this->extensionName;
    }
}
PHP
                ,
                <<<'PHP'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->request->getControllerExtensionName() === 'whatever') {

        }

        $extensionName = $this->request->getControllerExtensionName();
    }
}
PHP
            ),
        ]);
    }
}
