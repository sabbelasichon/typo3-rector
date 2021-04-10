<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v4;

use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Page\PageRepository;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.4/Deprecation-85558-ContentObjectRenderer-enableFields.html
 */
final class CallEnableFieldsFromPageRepositoryRector extends AbstractRector
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
            new ObjectType(ContentObjectRenderer::class)
        )) {
            return null;
        }
        if (! $this->isName($node->name, 'enableFields')) {
            return null;
        }
        $numberOfMethodArguments = count($node->args);
        if ($numberOfMethodArguments > 1) {
            $node->args[1] = new Arg(BuilderHelpers::normalizeValue(
                $this->valueResolver->isTrue($node->args[1]->value) ? true : -1
            ));
        }
        return $this->nodeFactory->createMethodCall(
            $this->nodeFactory->createStaticCall(
                GeneralUtility::class,
                'makeInstance',
                [$this->nodeFactory->createClassConstReference(PageRepository::class)]
            ),
            'enableFields',
            $node->args
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Call enable fields from PageRepository instead of ContentObjectRenderer', [
            new CodeSample(<<<'CODE_SAMPLE'
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->enableFields('pages', false, []);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
GeneralUtility::makeInstance(PageRepository::class)->enableFields('pages', -1, []);
CODE_SAMPLE
),
        ]);
    }
}
