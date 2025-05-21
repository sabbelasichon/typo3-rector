<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100662-ConfigurationManager-getContentObject.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateConfigurationManagerGetContentObjectRector\MigrateConfigurationManagerGetContentObjectRectorTest
 */
final class MigrateConfigurationManagerGetContentObjectRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate `ConfigurationManager->getContentObject()` to use request attribute instead',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $contentObject = $this->configurationManager->getContentObject();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $contentObject = $this->request->getAttribute('currentContentObject');
    }
}
CODE_SAMPLE
            ),

        ]);
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?MethodCall
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $requestFetcherVariable = $this->nodeFactory->createPropertyFetch('this', 'request');

        return $this->nodeFactory->createMethodCall($requestFetcherVariable, 'getAttribute', [
            $this->nodeFactory->createArg(new String_('currentContentObject')),
        ]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'getContentObject');
    }
}
