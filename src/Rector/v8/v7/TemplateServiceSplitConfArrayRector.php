<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v7;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\TypoScript\TemplateService;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.7/Deprecation-78650-TemplateService-splitConfArray.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v8\v7\TemplateServiceSplitConfArrayRector\TemplateServiceSplitConfArrayRectorTest
 */
final class TemplateServiceSplitConfArrayRector extends AbstractRector
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
            new ObjectType('TYPO3\CMS\Core\TypoScript\TemplateService')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'splitConfArray')) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                'TYPO3\CMS\Core\Utility\GeneralUtility',
                'makeInstance',
                [$this->nodeFactory->createClassConstReference('TYPO3\CMS\Core\TypoScript\TypoScriptService')]
            ),
            'explodeConfigurationForOptionSplit',
            $node->args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Substitute TemplateService->splitConfArray() with TypoScriptService->explodeConfigurationForOptionSplit()',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
CODE_SAMPLE
                ),
            ]
        );
    }
}
