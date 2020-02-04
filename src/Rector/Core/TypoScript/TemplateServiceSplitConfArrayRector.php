<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\TypoScript;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class TemplateServiceSplitConfArrayRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, TemplateService::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'splitConfArray')) {
            return null;
        }

        return $this->createMethodCall($this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [
                $this->createClassConstant(TypoScriptService::class, 'class'),
            ]
        ), 'explodeConfigurationForOptionSplit', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Substitute TemplateService->splitConfArray() with TypoScriptService->explodeConfigurationForOptionSplit()', [
            new CodeSample(
                <<<'PHP'
$splitConfig = GeneralUtility::makeInstance(TemplateService::class)->splitConfArray($conf, $splitCount);
PHP
                ,
                <<<'PHP'
$splitConfig = GeneralUtility::makeInstance(TypoScriptService::class)->explodeConfigurationForOptionSplit($conf, $splitCount);
PHP
            ),
        ]);
    }
}
