<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.6/Deprecation-70494-WrapClickMenuOnIcon.html
 */
final class WrapClickMenuOnIconRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, DocumentTemplate::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'wrapClickMenuOnIcon')) {
            return null;
        }

        return $this->createStaticCall(BackendUtility::class, 'wrapClickMenuOnIcon', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use method wrapClickMenuOnIcon of class BackendUtility', [
            new CodeSample('DocumentTemplate->wrapClickMenuOnIcon', 'BackendUtility::wrapClickMenuOnIcon()'),
        ]);
    }
}
