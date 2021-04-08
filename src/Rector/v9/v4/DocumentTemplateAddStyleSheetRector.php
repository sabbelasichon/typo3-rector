<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Backend\Template\DocumentTemplate;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85735-MethodAndPropertyInDocumentTemplate.html
 */
final class DocumentTemplateAddStyleSheetRector extends AbstractRector
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
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType($node, DocumentTemplate::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'addStyleSheet')) {
            return null;
        }

        $args = $node->args;

        if (! isset($args[0], $args[1])) {
            return null;
        }

        $href = $this->valueResolver->getValue($args[1]->value);
        $title = isset($args[2]) ? $this->valueResolver->getValue($args[2]->value) : '';
        $relation = isset($args[3]) ? $this->valueResolver->getValue($args[3]->value) : 'stylesheet';

        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(PageRenderer::class)]
            ),
            'addCssFile',
            [$href, $relation, 'screen', $title]
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use PageRenderer::addCssFile instead of DocumentTemplate::addStyleSheet() ', [
            new CodeSample(<<<'CODE_SAMPLE'
$documentTemplate = GeneralUtility::makeInstance(DocumentTemplate::class);
$documentTemplate->addStyleSheet('foo', 'foo.css');
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
GeneralUtility::makeInstance(PageRenderer::class)->addCssFile('foo.css', 'stylesheet', 'screen', '');
CODE_SAMPLE
        ),
        ]);
    }
}
