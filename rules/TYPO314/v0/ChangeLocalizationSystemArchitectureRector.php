<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Breaking-107436-LocalizationSystemChanges.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\ChangeLocalizationSystemArchitectureRector\ChangeLocalizationSystemArchitectureRectorTest
 */
final class ChangeLocalizationSystemArchitectureRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Localization system architecture changes', [new CodeSample(
            <<<'CODE_SAMPLE'
$localizationFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Localization\LocalizationFactory::class);
$data = $localizationFactory->getParsedData($fileReference, $languageKey, null, null, false);
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$localizationFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Localization\LocalizationFactory::class);
$data = $localizationFactory->getParsedData($fileReference, $languageKey);
CODE_SAMPLE
        )]);
    }

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
        if ($this->shouldSkip($node)) {
            return null;
        }

        $node->args = [$node->args[0], $node->args[1]];

        return $node;
    }

    private function shouldSkip(MethodCall $methodCall): bool
    {
        if (! $this->isName($methodCall->name, 'getParsedData')) {
            return true;
        }

        if (! $this->isObjectType(
            $methodCall->var,
            new ObjectType('TYPO3\CMS\Core\Localization\LocalizationFactory')
        )) {
            return true;
        }

        return count($methodCall->args) <= 2;
    }
}
