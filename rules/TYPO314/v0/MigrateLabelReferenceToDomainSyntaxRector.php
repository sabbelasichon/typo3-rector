<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Feature-93334-TranslationDomainMapping.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigrateLabelReferenceToDomainSyntaxRector\MigrateLabelReferenceToDomainSyntaxRectorTest
 */
final class MigrateLabelReferenceToDomainSyntaxRector extends AbstractRector implements DocumentedRuleInterface
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
        return new RuleDefinition('Migrate LLL references to the new domain-based notation', [new CodeSample(
            <<<'CODE_SAMPLE'
$lang->sL('LLL:EXT:my_site/Resources/Private/Language/locallang.xlf:my_table.my_field');
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
$lang->sL('my_site.messages:my_table.my_field');
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
        if (! $this->isName($node->name, 'sL')) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType('TYPO3\CMS\Core\Localization\LanguageService'))) {
            return null;
        }

        $firstArg = $node->args[0] ?? null;
        if ($firstArg === null) {
            return null;
        }

        $value = $this->valueResolver->getValue($firstArg->value);
        if (! is_string($value) || strpos($value, 'LLL:EXT:') !== 0) {
            return null;
        }

        $transformed = $this->transformLllString($value);
        if ($transformed === $value) {
            return null;
        }

        $node->args[0]->value = new String_($transformed);

        return $node;
    }

    private function transformLllString(string $lll): string
    {
        // Pattern to extract extension, path/file, and the translation key
        if (! preg_match('/^LLL:EXT:([^\/]+)\/(.*)\.xlf:(.*)$/', $lll, $matches)) {
            return $lll;
        }

        $extensionKey = $matches[1];
        $filePath = $matches[2];
        $translationKey = $matches[3];

        $domainParts = [$extensionKey];

        // Rule 4: Site Set labels receive the .sets prefix
        if (strpos($filePath, 'Configuration/Sets/') === 0) {
            $domainParts[] = 'sets';
            // Remove the prefix to process the remaining path
            $filePath = substr($filePath, strlen('Configuration/Sets/'));
        }

        // Rule 1: The base path Resources/Private/Language/ is omitted
        $baseLanguagePath = 'Resources/Private/Language/';
        if (strpos($filePath, $baseLanguagePath) === 0) {
            $filePath = substr($filePath, strlen($baseLanguagePath));
        }

        $pathSegments = explode('/', $filePath);
        foreach ($pathSegments as $index => $segment) {
            $isLast = $index === count($pathSegments) - 1;

            if ($isLast) {
                // Rule 6: Locale prefixes do not affect the identifier (e.g., de.locallang -> locallang)
                $segment = preg_replace('/^[a-z]{2}(-[A-Z]{2})?\./', '', $segment);

                // Rule 2: Handle standard filename patterns
                if ($segment === 'locallang') {
                    $domainParts[] = 'messages';
                } elseif (strpos($segment, 'locallang_') === 0) {
                    $domainParts[] = $this->camelToSnake(substr($segment, 10));
                } elseif ($segment === 'labels') {
                    // Standard for sets
                } else {
                    $domainParts[] = $this->camelToSnake($segment);
                }
            } else {
                // Rule 3: Subdirectories converted to dot notation
                $domainParts[] = $this->camelToSnake($segment);
            }
        }

        return implode('.', $domainParts) . ':' . $translationKey;
    }

    private function camelToSnake(string $input): string
    {
        // Rule 5: UpperCamelCase to snake_case
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
