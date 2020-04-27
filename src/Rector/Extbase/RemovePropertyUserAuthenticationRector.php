<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Extbase;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-71521-PropertyUserAuthenticationRemovedFromCommandController.html
 */
final class RemovePropertyUserAuthenticationRector extends AbstractRector
{
    /**
     * @return string[]
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
        if (! $this->isName($node, 'userAuthentication')) {
            return $node;
        }

        if (! $this->isObjectType($node->var, CommandController::class)) {
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
