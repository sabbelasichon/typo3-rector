<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/10.0/Deprecation-88499-BackendUtilitygetViewDomain.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v0\BackendUtilityGetViewDomainToPageRouterRector\BackendUtilityGetViewDomainToPageRouterRectorTest
 */
final class BackendUtilityGetViewDomainToPageRouterRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class, Return_::class];
    }

    /**
     * @param Expression|Return_ $node
     * @return Node[]|null
     */
    public function refactor(Node $node): ?array
    {
        $methodCall = null;
        if ($node instanceof Return_) {
            $methodCall = $node->expr;
        } elseif ($node->expr instanceof Assign) {
            $methodCall = $node->expr->expr;
        }

        if (! $methodCall instanceof StaticCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'getViewDomain')) {
            return null;
        }

        $siteAssign = new Expression(new Assign(new Variable('site'), $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall('TYPO3\CMS\Core\Utility\GeneralUtility', 'makeInstance', [
                $this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\Site\SiteFinder'),
            ]),
            'getSiteByPageId',
            $methodCall->args
        )));

        $methodCallGenerateUri = $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createMethodCall(new Variable('site'), 'getRouter'),
            'generateUri',
            [$methodCall->args[0]]
        );

        if ($node->expr instanceof Assign) {
            $node->expr->expr = $methodCallGenerateUri;
        } else {
            $node->expr = $methodCallGenerateUri;
        }

        return [$siteAssign, $node];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor method call `BackendUtility::getViewDomain()` to PageRouter', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Utility\BackendUtility;

$domain1 = BackendUtility::getViewDomain(1);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId(1);
$domain1 = $site->getRouter()->generateUri(1);
CODE_SAMPLE
            ),
        ]);
    }
}
