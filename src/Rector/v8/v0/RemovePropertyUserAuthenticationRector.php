<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-71521-PropertyUserAuthenticationRemovedFromCommandController.html
 */
final class RemovePropertyUserAuthenticationRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
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
            return null;
        }
        if (! $this->isObjectType($node->var, new ObjectType(CommandController::class))) {
            return null;
        }
        return $this->nodeFactory->createMethodCall($node->var, 'getBackendUserAuthentication');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use method getBackendUserAuthentication instead of removed property $userAuthentication',
            [
                new CodeSample(<<<'CODE_SAMPLE'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->userAuthentication !== null) {

        }
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class MyCommandController extends CommandController
{
    public function myMethod()
    {
        if($this->getBackendUserAuthentication() !== null) {

        }
    }
}
CODE_SAMPLE
),
            ]
        );
    }
}
