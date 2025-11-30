<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-97559-DeprecatePassingAnArrayOfConfigurationValuesToExtbaseAttributes.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePassingAnArrayOfConfigurationValuesToExtbaseAttributesRector\MigratePassingAnArrayOfConfigurationValuesToExtbaseAttributesRectorTest
 */
final class MigratePassingAnArrayOfConfigurationValuesToExtbaseAttributesRector extends AbstractRector implements DocumentedRuleInterface, MinPhpVersionInterface
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
        return new RuleDefinition('Migrate passing an array of configuration values to Extbase attributes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Attribute\FileUpload;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class MyModel extends AbstractEntity
{
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $foo = '';

    #[FileUpload([
        'validation' => [
            'required' => true,
            'maxFiles' => 1,
            'fileSize' => ['minimum' => '0K', 'maximum' => '2M'],
            'allowedMimeTypes' => ['image/jpeg', 'image/png'],
        ],
        'uploadFolder' => '1:/user_upload/files/',
    ])]
    protected ?FileReference $bar = null;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use TYPO3\CMS\Extbase\Attribute\FileUpload;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

class MyModel extends AbstractEntity
{
    #[Validate(validator: 'NotEmpty')]
    protected string $foo = '';

    #[FileUpload(
        validation: [
            'required' => true,
            'maxFiles' => 1,
            'fileSize' => ['minimum' => '0K', 'maximum' => '2M'],
            'allowedMimeTypes' => ['image/jpeg', 'image/png'],
        ],
        uploadFolder: '1:/user_upload/files/',
    )]
    protected ?FileReference $bar = null;
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @param Attribute $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        /** @var Arg $firstArg */
        $firstArg = $node->args[0];

        /** @var Array_ $value */
        $value = $firstArg->value;

        // Special case: \TYPO3\CMS\Extbase\Attribute\ORM\Cascade(['value' => '...'])
        if (count($value->items) === 1 && $this->isName($node->name, 'TYPO3\CMS\Extbase\Attribute\ORM\Cascade')) {
            $arrayItem = $value->items[0];

            if ($arrayItem instanceof ArrayItem && $arrayItem->key instanceof Node) {
                $keyName = $this->valueResolver->getValue($arrayItem->key);

                // If the key is 'value', transform to a single positional argument
                if ($keyName === 'value') {
                    $node->args = [new Arg($arrayItem->value)];
                    return $node;
                }
            }
        }

        $newArgs = [];

        foreach ($value->items as $item) {
            if (! $item instanceof ArrayItem) {
                return null;
            }

            // We cannot proceed if the array key is missing (indexed array)
            if (! $item->key instanceof Node) {
                return null;
            }

            $argumentName = $this->valueResolver->getValue($item->key);

            // We can only convert if the key is a string to be used as a named argument
            if (! is_string($argumentName)) {
                return null;
            }

            $newArgs[] = new Arg($item->value, false, false, [], new Identifier($argumentName));
        }

        $node->args = $newArgs;

        return $node;
    }

    private function shouldSkip(Attribute $node): bool
    {
        $args = $node->args;

        if (count($args) !== 1) {
            return true;
        }

        $firstArg = $args[0];

        if ($firstArg->name instanceof Identifier) {
            return true;
        }

        $value = $firstArg->value;
        if (! $value instanceof Array_) {
            return true;
        }

        return ! $this->isNames($node->name, [
            'TYPO3\CMS\Extbase\Attribute\FileUpload',
            'TYPO3\CMS\Extbase\Attribute\IgnoreValidation',
            'TYPO3\CMS\Extbase\Attribute\ORM\Cascade',
            'TYPO3\CMS\Extbase\Attribute\Validate',
        ]);
    }
}
