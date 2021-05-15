<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.6/Deprecation-70494-WrapClickMenuOnIcon.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v7\v6\WrapClickMenuOnIconRector\WrapClickMenuOnIconRectorTest
 */
final class WrapClickMenuOnIconRector extends AbstractRector
{
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Backend\Template\DocumentTemplate')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'wrapClickMenuOnIcon')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Backend\Utility\BackendUtility',
            'wrapClickMenuOnIcon',
            $node->args
        );
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
