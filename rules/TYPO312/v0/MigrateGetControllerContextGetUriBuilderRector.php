<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Migrate calls to ActionController->getControllerContext()->getUriBuilder() to ->uriBuilder
 *
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96107-DeprecatedFunctionalityRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateGetControllerContextGetUriBuilderRector\MigrateGetControllerContextGetUriBuilderRectorTest
 */
final class MigrateGetControllerContextGetUriBuilderRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate extbase controller calls $this->getControllerContext()->getUriBuilder(); to ->uriBuilder',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class DummyController extends ActionController
{
    public function showAction(): ResponseInterface
    {
        $url = $this->getControllerContext()->getUriBuilder()
            ->setTargetPageType(10002)
            ->uriFor('addresses');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class DummyController extends ActionController
{
    public function showAction(): ResponseInterface
    {
        $url = $this->uriBuilder
            ->setTargetPageType(10002)
            ->uriFor('addresses');
    }
}
CODE_SAMPLE
                )
            ]
        );
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param \PhpParser\Node $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        return $this->nodeFactory->createPropertyFetch(
            'this', 'uriBuilder'
        );
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->isName($node->name, 'getUriBuilder')) {
            return true;
        }

        if (! $this->isName($node->var->name, 'getControllerContext')) {
            return true;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node->var,
            new ObjectType('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ActionController')
        )) {
            return true;
        }

        return false;
    }
}
