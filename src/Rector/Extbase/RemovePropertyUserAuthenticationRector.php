<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

final class RemovePropertyUserAuthenticationRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Node\Expr\PropertyFetch::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'userAuthentication')) {
            return $node;
        }

        if (!$this->isObjectType($node->var, CommandController::class)) {
            return $node;
        }

        return $this->createMethodCall(
            $node->var,
            'getBackendUserAuthentication'
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use method getBackendUserAuthentication instead of removed property $userAuthentication', [
            new CodeSample(
                <<<'PHP'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->userAuthentication !== null) {

        }
    }
}
PHP
                ,
                <<<'PHP'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->getBackendUserAuthentication() !== null) {

        }
    }
}
PHP
            ),
        ]);
    }
}
