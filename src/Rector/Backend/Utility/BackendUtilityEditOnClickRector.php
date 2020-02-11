<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Backend\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.1/Deprecation-88787-BackendUtilityEditOnClick.html
 */
final class BackendUtilityEditOnClickRector extends AbstractRector
{
    /**
     * @param StaticCall $node
     *
     * @return Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, BackendUtility::class)) {
            return null;
        }

        if (!$this->isName($node, 'editOnClick')) {
            return null;
        }

        /** @var Node\Arg[] $args */
        $args = $node->args;
        $firstArgument = array_shift($args);

        return new Concat(
            $this->createUriBuilderCall($firstArgument),
            $this->createRequestUriCall()
        );
    }

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrate the method BackendUtility::editOnClick() to use UriBuilder API', [
            new CodeSample(
                <<<'PHP'
$pid = 2;
$params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
$url = BackendUtility::editOnClick($params);
PHP
                ,
                <<<'PHP'
$pid = 2;
$params = '&edit[pages][' . $pid . ']=new&returnNewPageId=1';
$url = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute('record_edit') . $params . '&returnUrl=' . rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'));;
PHP
            ),
        ]);
    }

    private function createUriBuilderCall(Node\Arg $firstArgument): Concat
    {
        return new Concat(
            new Concat(
                $this->createMethodCall(
                    $this->createStaticCall(
                        GeneralUtility::class,
                        'makeInstance',
                        [
                            $this->createClassConstant(UriBuilder::class, 'class'),
                        ]
                    ), 'buildUriFromRoute', [$this->createArg('record_edit')]
                ),
                $firstArgument->value
            ),
            new Node\Scalar\String_('&returnUrl=')
        );
    }

    private function createRequestUriCall(): Node\Expr\FuncCall
    {
        return new Node\Expr\FuncCall(
            new Node\Name('rawurlencode'), [
                $this->createArg(
                    $this->createStaticCall(
                        GeneralUtility::class,
                        'getIndpEnv',
                        [
                            $this->createArg('REQUEST_URI'),
                        ]
                    )
                ),
            ]
        );
    }
}
