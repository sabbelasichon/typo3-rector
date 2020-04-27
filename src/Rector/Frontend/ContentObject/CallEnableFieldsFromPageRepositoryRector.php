<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Frontend\ContentObject;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85558-ContentObjectRenderer-enableFields.html
 */
final class CallEnableFieldsFromPageRepositoryRector extends AbstractRector
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
