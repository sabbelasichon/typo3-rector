<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Page\PageRepository;

final class CallEnableFieldsFromPageRepositoryRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|MethodCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, ContentObjectRenderer::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'enableFields')) {
            return null;
        }

        $numberOfMethodArguments = count($node->args);
        if ($numberOfMethodArguments > 1) {
            $node->args[1] = new Node\Arg(BuilderHelpers::normalizeValue($this->isTrue($node->args[1]->value) ? true : -1));
        }

        return $this->createMethodCall($this->createStaticCall(
            GeneralUtility::class,
            'makeInstance',
            [
                $this->createClassConstant(PageRepository::class, 'class'),
            ]
        ), 'enableFields', $node->args);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Call enable fields from PageRepository instead of ContentObjectRenderer', [
            new CodeSample(
                <<<'PHP'
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->enableFields('pages', false, []);
PHP
                ,
                <<<'PHP'
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
PHP
            ),
        ]);
    }
}
