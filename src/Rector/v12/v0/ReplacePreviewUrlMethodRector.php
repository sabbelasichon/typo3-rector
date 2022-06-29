<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v12\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Core\Console\Output\RectorOutputStyle;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-97544-PreviewURIGenerationRelatedFunctionalityInBackendUtility.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\ReplacePreviewUrlMethodRector\ReplacePreviewUrlMethodRectorTest
 */
final class ReplacePreviewUrlMethodRector extends AbstractRector
{
    /**
     * @var string
     */
    private const MESSAGE = 'Rector changed the BackendUtility::getPreviewUrl call, but there might be further steps necessary. See Deprecation-97544-PreviewURIGenerationRelatedFunctionalityInBackendUtility.html';

    /**
     * @readonly
     */
    private RectorOutputStyle $rectorOutputStyle;

    public function __construct(RectorOutputStyle $rectorOutputStyle)
    {
        $this->rectorOutputStyle = $rectorOutputStyle;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param Node\Expr\StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (isset($node->args[6])) {
            $varName = $this->nodeNameResolver->getName($node->args[6]->value) ?? '';

            $dispatchArgs = new Array_([
                new ArrayItem(
                    new Variable($varName),
                    $this->nodeFactory->createClassConstFetch(
                        'TYPO3\CMS\Backend\Routing\PreviewUriBuilder',
                        'OPTION_SWITCH_FOCUS'
                    )
                ),
            ]);
        }

        $createCall = $this->nodeFactory->createStaticCall(
            'TYPO3\CMS\Backend\Routing\PreviewUriBuilder',
            'create',
            [$node->args[0]]
        );

        $chainCalls = $createCall;

        if (isset($node->args[2])) {
            $chainCalls = $this->nodeFactory->createMethodCall($createCall, 'withRootLine', [$node->args[2]]);
        }

        if (isset($node->args[3])) {
            $chainCalls = $this->nodeFactory->createMethodCall($chainCalls, 'withSection', [$node->args[3]]);
        }

        if (isset($node->args[5])) {
            $chainCalls = $this->nodeFactory->createMethodCall(
                $chainCalls,
                'withAdditionalQueryParameters',
                [$node->args[5]]
            );
        }

        $this->rectorOutputStyle->warning(self::MESSAGE);
        $methodCall = $this->nodeFactory->createMethodCall(
            $chainCalls,
            'buildUri',
            isset($dispatchArgs) ? [$dispatchArgs->items] : []
        );

        return new String_($methodCall);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace getPreviewUrl', [new CodeSample(
            <<<'CODE_SAMPLE'
$foo = BackendUtility::getPreviewUrl(
    $pageUid,
    $backPath,
    $rootLine,
    $anchorSection,
    $alternativeUrl,
    $additionalGetVars,
    &$switchFocus
);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$foo = (string) PreviewUriBuilder::create($pageUid)
    ->withRootLine($rootLine)
    ->withSection($anchorSection)
    ->withAdditionalQueryParameters($additionalGetVars)
    ->buildUri([
        PreviewUriBuilder::OPTION_SWITCH_FOCUS => $switchFocus,
    ]);
CODE_SAMPLE
        )]);
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Backend\Utility\BackendUtility')
        )) {
            return true;
        }

        return ! $this->isName($staticCall->name, 'getPreviewUrl');
    }
}
