<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v7\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/7.5/Deprecation-69754-TcaCtrlIconfileUsingRelativePathToExtAndFilenameOnly.html
 */
final class UseExtPrefixForTcaIconFileRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Deprecate relative path to extension directory and using filename only in TCA ctrl iconfile',
            [
                new CodeSample(<<<'PHP'
return [
    'ctrl' => [
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('foo').'Resources/Public/Icons/image.png',
    ],
];
PHP
                    , <<<'PHP'
return [
    'ctrl' => [
        'iconfile' => 'EXT:foo/Resources/Public/Icons/image.png',
    ],
];
PHP
                ),

            ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    /**
     * @param ArrayItem $node
     */
    public function refactor(Node $node): ?Node
    {
        if (null === $node->key) {
            return null;
        }

        if (! $this->isValue($node->key, 'iconfile')) {
            return null;
        }

        if (! $node->value instanceof Concat) {
            return null;
        }

        $staticCall = $node->value->left;
        if (! $staticCall instanceof StaticCall) {
            return null;
        }

        if (! $this->isMethodStaticCallOrClassMethodObjectType($staticCall, ExtensionManagementUtility::class)) {
            return null;
        }

        if (! $this->isName($staticCall->name, 'extRelPath')) {
            return null;
        }

        if (0 === count($staticCall->args)) {
            return null;
        }

        $extensionKey = $this->getValue($staticCall->args[0]->value);

        if (null === $extensionKey) {
            return null;
        }

        if (! $node->value->right instanceof String_) {
            return null;
        }

        $pathToIconFile = $this->getValue($node->value->right);

        if (null === $pathToIconFile) {
            return null;
        }

        $node->value = new String_(sprintf('EXT:%s/%s', $extensionKey, $pathToIconFile));

        return $node;
    }
}
