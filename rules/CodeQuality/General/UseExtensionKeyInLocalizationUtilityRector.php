<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Ssch\TYPO3Rector\Contract\NoChangelogRequiredInterface;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * This rector fixes a common error in TYPO3 installations to use the extension key where the extension name is required
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\UseExtensionKeyInLocalizationUtilityRector\UseExtensionKeyInLocalizationUtilityRectorTest
 */
class UseExtensionKeyInLocalizationUtilityRector extends AbstractRector implements NoChangelogRequiredInterface
{
    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace the second parameter of LocalizationUtility::translate to the extension name',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
LocalizationUtility::translate('key', 'extension_key');
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
LocalizationUtility::translate('key', 'ExtensionName');
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'translate')) {
            return null;
        }

        $arguments = $node->args;
        if (count($arguments) < 2) {
            return null;
        }

        return $this->removeVendorNameIfNeeded($node);
    }

    private function shouldSkip(StaticCall $staticCall): bool
    {
        return ! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $staticCall,
            new ObjectType('TYPO3\CMS\Extbase\Utility\LocalizationUtility')
        );
    }

    private function removeVendorNameIfNeeded(StaticCall $staticCall): ?Node
    {
        $secondArgument = $staticCall->getArgs()[1];
        $extensionKey = $this->valueResolver->getValue($secondArgument->value);
        if (! is_string($extensionKey)) {
            return null;
        }

        $delimiterPosition = strpos($extensionKey, '_');
        if ($delimiterPosition === false) {
            return null;
        }

        $extensionName = StringUtility::extensionKeyToExtensionName($extensionKey);
        $staticCall->args[1] = $this->nodeFactory->createArg($extensionName);
        return $staticCall;
    }
}
