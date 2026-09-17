<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO313\v0;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\NodeFactory\Typo3RequestNodeFactory;
use Ssch\TYPO3Rector\NodeResolver\Typo3NodeResolver;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/13.0/Breaking-102605-TSFE-fe_userRemoved.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v13\v0\MigrateTypoScriptFrontendControllerFeUserRector\MigrateTypoScriptFrontendControllerFeUserRectorTest
 */
final class MigrateTypoScriptFrontendControllerFeUserRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Migrate `$GLOBALS[\'TSFE\']->fe_user` to use the request attribute', [new CodeSample(
            <<<'CODE_SAMPLE'
$frontendUser = $GLOBALS['TSFE']->fe_user;

$GLOBALS['TSFE']->fe_user->setKey('ses', 'key', 'value');

if (is_array($GLOBALS['TSFE']->fe_user->user) && $GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
    $id = $GLOBALS['TSFE']->fe_user->user['uid'];
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$frontendUser = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user');

$GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->setKey('ses', 'key', 'value');

if (is_array($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user) && $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'] > 0) {
    $id = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.user')->user['uid'];
}
CODE_SAMPLE
        ), new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MyActionController extends ActionController
{
    public function myMethod(): void
    {
        $frontendUser = $GLOBALS['TSFE']->fe_user;

        if (is_array($GLOBALS['TSFE']->fe_user->user) && $GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
            $id = $GLOBALS['TSFE']->fe_user->user['uid'];
        }
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
        $frontendUser = $this->request->getAttribute('frontend.user');

        if (is_array($this->request->getAttribute('frontend.user')->user) && $this->request->getAttribute('frontend.user')->user['uid'] > 0) {
            $id = $this->request->getAttribute('frontend.user')->user['uid'];
        }
    }
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class, MethodCall::class, PropertyFetch::class];
    }

    /**
     * @param Assign|MethodCall|PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        $readExpression = $this->getReadExpression($node);
        if ($readExpression instanceof PropertyFetch && $this->isFeUserPropertyFetch($readExpression)) {
            $replacement = $this->createReplacement($readExpression);
            $this->replaceReadExpression($node, $replacement);

            return $node;
        }

        // Handle standalone $GLOBALS['TSFE']->fe_user (e.g. in return statements or conditions)
        if ($node instanceof PropertyFetch
            && ! $node->getAttribute(AttributeKey::IS_BEING_ASSIGNED)
            && $this->isFeUserPropertyFetch($node)
        ) {
            return $this->createReplacement($node);
        }

        return null;
    }

    private function getReadExpression(Node $node): ?Expr
    {
        if ($node instanceof Assign) {
            return $node->expr;
        }

        if ($node instanceof MethodCall || $node instanceof PropertyFetch) {
            return $node->var;
        }

        return null;
    }

    /**
     * Checks if the node is a property fetch of `$tsfe->fe_user`.
     */
    private function isFeUserPropertyFetch(PropertyFetch $propertyFetch): bool
    {
        if (! $this->isName($propertyFetch->name, 'fe_user')) {
            return false;
        }

        if ($this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $propertyFetch,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        )) {
            return true;
        }

        return $this->isObjectType(
            $propertyFetch->var,
            new ObjectType('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController')
        );
    }

    private function createReplacement(PropertyFetch $propertyFetch): MethodCall
    {
        $scope = ScopeFetcher::fetch($propertyFetch);

        return $this->nodeFactory->createMethodCall(
            $this->typo3RequestNodeFactory->getServerRequestInScope($scope),
            'getAttribute',
            ['frontend.user']
        );
    }

    /**
     * @param Assign|MethodCall|PropertyFetch $node
     */
    private function replaceReadExpression(Node $node, MethodCall $replacement): void
    {
        if ($node instanceof Assign) {
            $node->expr = $replacement;
        } elseif ($node instanceof MethodCall || $node instanceof PropertyFetch) {
            $node->var = $replacement;
        }
    }
}
