<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\Controller;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85666-TypoScriptFrontendController-initTemplate.html
 */
final class RemoveInitTemplateMethodCallRector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Expression::class];
    }

    /**
     * @param Expression|MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->typo3NodeResolver->isMethodCallOnGlobals(
            $node,
            'initTemplate',
            Typo3NodeResolver::TypoScriptFrontendController
        )) {
            $this->removeNode($node);

            return null;
        }

        if (! $node instanceof MethodCall) {
            return null;
        }

        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'initTemplate')) {
            return null;
        }

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            $this->removeNode($parentNode);
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove method call initTemplate from TypoScriptFrontendController', [
            new CodeSample(
                <<<'PHP'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
$tsfe->initTemplate();
PHP
                ,
                <<<'PHP'
$tsfe = GeneralUtility::makeInstance(TypoScriptFrontendController::class);
PHP
            ),
        ]);
    }
}
