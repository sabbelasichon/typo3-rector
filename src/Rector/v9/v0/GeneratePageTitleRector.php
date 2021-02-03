<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Page\PageGenerator;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-83254-MovedPageGenerationMethodsIntoTSFE.html
 */
final class GeneratePageTitleRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, PageGenerator::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'generatePageTitle')) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(new ArrayDimFetch(
            new Variable('GLOBALS'),
            new String_(Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER)
        ), 'generatePageTitle');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use generatePageTitle of TSFE instead of class PageGenerator',
            [
                new CodeSample(
                    <<<'PHP'
use TYPO3\CMS\Frontend\Page\PageGenerator;

PageGenerator::generatePageTitle();
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Frontend\Page\PageGenerator;

$GLOBALS['TSFE']->generatePageTitle();
PHP
                ),
            ]);
    }
}
