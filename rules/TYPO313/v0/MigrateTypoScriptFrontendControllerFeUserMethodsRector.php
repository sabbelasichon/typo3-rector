<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3RequestNodeFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102605-TSFE-fe_userRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserMethodsRector\MigrateTypoScriptFrontendControllerFeUserMethodsRectorTest
 */
final class MigrateTypoScriptFrontendControllerFeUserMethodsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @readonly
     */
    private Typo3NodeResolver $typo3NodeResolver;

    /**
     * @readonly
     */
    private Typo3RequestNodeFactory $typo3RequestNodeFactory;

    public function __construct(
        Typo3RequestNodeFactory $typo3RequestNodeFactory,
        Typo3NodeResolver $typo3NodeResolver
    ) {
        $this->typo3RequestNodeFactory = $typo3RequestNodeFactory;
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate `$GLOBALS[\'TSFE\']->fe_user->xxx()` methods to use the request attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$GLOBALS['TSFE']->fe_user->setKey('ses', 'extension', 'value');
$GLOBALS['TSFE']->fe_user->getKey('ses', 'extension');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'extension', 'value');
$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->getKey('ses', 'extension');
CODE_SAMPLE
            ),
        ]);
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

        $node->var = $this->nodeFactory->createMethodCall(
            $this->typo3RequestNodeFactory->getServerRequestInScope(ScopeFetcher::fetch($node)),
            'getAttribute',
            ['frontend.user']
        );

        return $node;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        $propertyFetch = $methodCall->var;
        if (! $propertyFetch instanceof PropertyFetch) {
            return true;
        }

        if (! $this->isName($propertyFetch->name, 'fe_user')) {
            return true;
        }

        return ! $this->isGlobals($propertyFetch)
            && ! $this->isObjectType(
                $propertyFetch->var,
                new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
            );
    }

    private function isGlobals(PropertyFetch $propertyFetch): bool
    {
        return $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
