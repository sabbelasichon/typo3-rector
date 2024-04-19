<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v4;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.4/Deprecation-100637-ThirdArgumentContentObjectRenderer-start.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v4\MigrateRequestArgumentFromMethodStartRector\MigrateRequestArgumentFromMethodStartRectorTest
 */
final class MigrateRequestArgumentFromMethodStartRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use method setRequest of ContentObjectRenderer instead of third argument of method start',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->start([], 'pages', $GLOBALS['TYPO3_REQUEST']);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

$contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
$contentObjectRenderer->setRequest($GLOBALS['TYPO3_REQUEST']);
$contentObjectRenderer->start([], 'pages');
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return null|Node[]
     */
    public function refactor(Node $node): ?array
    {
        $methodCall = $node->expr;

        if (! $methodCall instanceof Node\Expr\MethodCall) {
            return null;
        }

        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $methodCall,
            new ObjectType('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer')
        )) {
            return null;
        }

        if (! $this->isName($methodCall->name, 'start')) {
            return null;
        }

        $requestArgument = $methodCall->args[2] ?? null;

        if ($requestArgument === null) {
            return null;
        }

        unset($methodCall->args[2]);

        $methodCallSetRequest = $this->nodeFactory->createMethodCall(
            $methodCall->var,
            'setRequest',
            [$requestArgument]
        );

        return [new Expression($methodCallSetRequest), $node];
    }
}
