<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\Contract\DocumentedRuleInterface;
use Rector\RuleDoc\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Breaking-96205-RemovalOfLastRelativeToCurrentScriptRemains.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\RemoveRelativeToCurrentScriptArgumentsRectorTest
 */
final class RemoveRelativeToCurrentScriptArgumentsRector extends AbstractRector implements DocumentedRuleInterface
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, Class_::class];
    }

    /**
     * @param MethodCall|Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        return $this->refactorClassMethod($node);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes all usages of the relativeToCurrentScript parameter', [
            new CodeSample(
                <<<'CODE_SAMPLE'
/** @var AudioTagRenderer $audioTagRenderer */
$audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
$foo = $audioTagRenderer->render($file, $width, $height, $options, $relative);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
/** @var AudioTagRenderer $audioTagRenderer */
$audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
$foo = $audioTagRenderer->render($file, $width, $height, $options);
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkipMethodCall(MethodCall $node): bool
    {
        if (! $this->isObjectType(
            $node->var,
            new ObjectType('TYPO3\\CMS\\Core\\Resource\\Rendering\\FileRendererInterface')
        )) {
            return true;
        }

        if (! $this->isName($node->name, 'render')) {
            return true;
        }

        return ! isset($node->getArgs()[4]);
    }

    private function refactorMethodCall(MethodCall $node): ?MethodCall
    {
        if ($this->isAvatarImageGetUrl($node)) {
            return $this->refactorAvatarImageGetUrl($node);
        }

        if ($this->shouldSkipMethodCall($node)) {
            return null;
        }

        unset($node->args[4]);

        return $node;
    }

    private function isAvatarImageGetUrl(MethodCall $methodCall): bool
    {
        if (! $this->isName($methodCall->name, 'getUrl')) {
            return false;
        }

        return $this->isObjectType($methodCall->var, new ObjectType('TYPO3\\CMS\\Backend\\Backend\\Avatar\\Image'));
    }

    private function refactorAvatarImageGetUrl(MethodCall $methodCall): ?MethodCall
    {
        if ($methodCall->isFirstClassCallable()) {
            return null;
        }

        if (! isset($methodCall->getArgs()[0])) {
            return null;
        }

        unset($methodCall->args[0]);

        return $methodCall;
    }

    private function refactorClassMethod(Class_ $node): ?Class_
    {
        $skip = true;
        foreach ($node->implements as $implement) {
            if ($this->isName($implement, 'TYPO3\\CMS\\Core\\Resource\\Rendering\\FileRendererInterface')) {
                $skip = false;
            }
        }

        if ($skip) {
            return null;
        }

        $classMethods = $node->getMethods();

        $hasChanged = false;
        foreach ($classMethods as $classMethod) {
            if (! $this->isName($classMethod->name, 'render')) {
                continue;
            }

            if (! isset($classMethod->params[4])) {
                continue;
            }

            unset($classMethod->params[4]);

            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }
}
