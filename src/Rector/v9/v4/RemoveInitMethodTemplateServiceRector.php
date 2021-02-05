<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\TypoScript\TemplateService;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85408-TemplateServiceInitDeprecated.html
 */
final class RemoveInitMethodTemplateServiceRector extends AbstractRector
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
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, TemplateService::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'init')) {
            return null;
        }

        $this->removeNode($node);

        return $node;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove method call init of class TemplateService', [
            new CodeSample(<<<'PHP'
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$templateService = GeneralUtility::makeInstance(TemplateService::class);
$templateService->init();
PHP
                , <<<'PHP'
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$templateService = GeneralUtility::makeInstance(TemplateService::class);
PHP
            ),
        ]);
    }
}
