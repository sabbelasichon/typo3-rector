<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Helper;

use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Ssch\TYPO3Rector\ComposerExtensionKeyResolver;

trait ExtensionKeyResolverTrait
{
    /**
     * @readonly
     */
    private ComposerExtensionKeyResolver $composerExtensionKeyResolver;

    /**
     * @param Variable|String_ $contentArgumentValue
     */
    private function resolvePotentialExtensionKeyByExtensionKeyParameter($contentArgumentValue): ?String_
    {
        if ($contentArgumentValue instanceof String_) {
            return null;
        }

        if (! $contentArgumentValue instanceof Variable) {
            return null;
        }

        $resolvedExtensionKey = $this->composerExtensionKeyResolver->resolveExtensionKey($this->file);
        if ($resolvedExtensionKey === null) {
            return null;
        }

        return new String_($resolvedExtensionKey);
    }

    /**
     * @param Concat|String_ $contentArgumentValue
     */
    private function resolvePotentialExtensionKeyByConcatenation($contentArgumentValue): void
    {
        if ($contentArgumentValue instanceof String_) {
            // If it is a string, it must contain the extension key
            return;
        }

        if (! $contentArgumentValue->left instanceof Concat) {
            return;
        }

        if (! $contentArgumentValue->left->right instanceof Variable) {
            return;
        }

        // If the extension key is a typical variable, then we can replace it. Otherwise, we can't do anything.
        if (! $this->isNames($contentArgumentValue->left->right, ['_EXTKEY', 'extensionKey'])) {
            return;
        }

        $resolvedExtensionKey = $this->composerExtensionKeyResolver->resolveExtensionKey($this->file);
        if ($resolvedExtensionKey === null) {
            return;
        }

        $contentArgumentValue->left->right = new String_($resolvedExtensionKey);
    }
}
