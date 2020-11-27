<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Frontend\Plugin\AbstractPlugin;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.2/Deprecation-71917-DeprecateTheArgumentHscForGetLLGetLLLAndSL.html
 */
final class UseHtmlSpecialCharsDirectlyForTranslationRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('htmlspecialchars directly to properly escape the content.', [
            new CodeSample(<<<'PHP'
PHP
                , <<<'PHP'
PHP
            ),
        ]);
    }

    /**
     * @return string[]
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
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'pi_getLL')) {
            return null;
        }

        if (! isset($node->args[2])) {
            return null;
        }

        $hsc = $this->getValue($node->args[2]->value);

        if (null === $hsc) {
            return null;
        }

        // If you don´t unset it you will end up in an infinite loop here
        unset($node->args[2]);

        if (false === $hsc) {
            return null;
        }

        return $this->createFuncCall('htmlspecialchars', [$node]);
    }

    private function shouldSkip(MethodCall $node): bool
    {
        return ! $this->isMethodStaticCallOrClassMethodObjectType($node, AbstractPlugin::class);
    }
}
