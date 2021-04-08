<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85701-MethodsInModuleTemplate.html
 */
final class UseAddJsFileInsteadOfLoadJavascriptLibRector extends AbstractRector
{
    /**
     * @return array<class-string<\PhpParser\Node>>
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, ModuleTemplate::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'loadJavascriptLib')) {
            return null;
        }

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(PageRenderer::class)]
            ),
            'addJsFile',
            $node->args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use method addJsFile of class PageRenderer instead of method loadJavascriptLib of class ModuleTemplate',
            [
                new CodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
$moduleTemplate->loadJavascriptLib('sysext/backend/Resources/Public/JavaScript/md5.js');
CODE_SAMPLE
                            , <<<'CODE_SAMPLE'
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
$moduleTemplate = GeneralUtility::makeInstance(ModuleTemplate::class);
GeneralUtility::makeInstance(PageRenderer::class)->addJsFile('sysext/backend/Resources/Public/JavaScript/md5.js');
CODE_SAMPLE
                        ),

            ]);
    }
}
