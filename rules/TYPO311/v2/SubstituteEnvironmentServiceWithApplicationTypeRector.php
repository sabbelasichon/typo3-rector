<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3GlobalsFactory;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.2/Deprecation-92494-ExtbaseEnvironmentService.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v2\SubstituteEnvironmentServiceWithApplicationTypeRector\SubstituteEnvironmentServiceWithApplicationTypeRectorTest
 */
final class SubstituteEnvironmentServiceWithApplicationTypeRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3GlobalsFactory $typo3GlobalsFactory;

    public function __construct(Typo3GlobalsFactory $typo3GlobalsFactory)
    {
        $this->typo3GlobalsFactory = $typo3GlobalsFactory;
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $factoryCall = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Core\Http\ApplicationType',
            'fromRequest',
            [$this->typo3GlobalsFactory->create('TYPO3_REQUEST')]
        );

        $method = $this->isName($node->name, 'isEnvironmentInFrontendMode') ? 'isFrontend' : 'isBackend';

        return $this->nodeFactory->createMethodCall($factoryCall, $method);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Substitute class EnvironmentService with ApplicationType class', [new CodeSample(
            <<<'CODE_SAMPLE'
if ($this->environmentService->isEnvironmentInFrontendMode()) {
    ...
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
if (ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend())
    ...
}
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\\CMS\\Extbase\\Service\\EnvironmentService')
        )) {
            return true;
        }

        return ! $this->isNames($node->name, ['isEnvironmentInFrontendMode', 'isEnvironmentInBackendMode']);
    }
}
