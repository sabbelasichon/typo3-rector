<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97549-ContentObjectRenderer-lastTypoLinkProperties.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateContentObjectRendererLastTypoLinkPropertiesRector\MigrateContentObjectRendererLastTypoLinkPropertiesRectorTest
 */
final class MigrateContentObjectRendererLastTypoLinkPropertiesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate lastTypoLink properties from ContentObjectRenderer', [new CodeSample(
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkUrl;
$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkTarget;
$lastTypoLinkLD = $contentObjectRenderer->lastTypoLinkLD;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$lastTypoLinkUrl = $contentObjectRenderer->lastTypoLinkResult->getUrl();
$lastTypoLinkTarget = $contentObjectRenderer->lastTypoLinkResult->getTarget();
$lastTypoLinkLD = ['target' => htmlspecialchars($contentObjectRenderer->lastTypoLinkResult->getTarget()), 'totalUrl' => $contentObjectRenderer->lastTypoLinkResult->getUrl(), 'type' => $contentObjectRenderer->lastTypoLinkResult->getType()];
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($node->getAttribute(AttributeKey::IS_BEING_ASSIGNED) !== null) {
            return null;
        }

        $propertyName = $node->name;
        $node->name = new Identifier('lastTypoLinkResult');

        if ($this->isName($propertyName, 'lastTypoLinkUrl')) {
            return $this->nodeFactory->createMethodCall($node, 'getUrl');
        }

        if ($this->isName($propertyName, 'lastTypoLinkTarget')) {
            return $this->nodeFactory->createMethodCall($node, 'getTarget');
        }

        return $this->nodeFactory->createArray([
            'target' => $this->nodeFactory->createFuncCall(
                'htmlspecialchars',
                [$this->nodeFactory->createMethodCall($node, 'getTarget')]
            ),
            'totalUrl' => $this->nodeFactory->createMethodCall($node, 'getUrl'),
            'type' => $this->nodeFactory->createMethodCall($node, 'getType'),
        ]);
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if (! $this->nodeTypeResolver->isObjectType(
            $node->var,
            new ObjectType('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer')
        )) {
            return true;
        }

        return ! $this->isNames($node->name, ['lastTypoLinkUrl', 'lastTypoLinkTarget', 'lastTypoLinkLD']);
    }
}
