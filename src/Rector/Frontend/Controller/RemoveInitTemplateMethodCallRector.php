<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\Controller;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Expression::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|Expression $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->typo3NodeResolver->isMethodCallOnGlobals($node, 'initTemplate', Typo3NodeResolver::TypoScriptFrontendController)) {
            $this->removeNode($node);

            return null;
        }

        if (!$node instanceof MethodCall) {
            return null;
        }

        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, TypoScriptFrontendController::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'initTemplate')) {
            return null;
        }

        try {
            $this->removeNode($node);
        } catch (ShouldNotHappenException $e) {
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
