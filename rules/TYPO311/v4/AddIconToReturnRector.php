<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO311\v4;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Return_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Icon/Index.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v11\v4\AddIconToReturnRector\AddIconToReturnRectorTest
 */
final class AddIconToReturnRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    public const OPTIONS = 'options';

    private string $identifier;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add new icon in return array', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
return [
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'my-icon' => [
        'provider' => \stdClass::class,
        'source' => 'mysvg.svg'
    ]
];
CODE_SAMPLE
                ,
                [
                    self::IDENTIFIER => 'my-icon',
                    self::OPTIONS => [
                        'provider' => \stdClass::class,
                        'source' => 'mysvg.svg',
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Return_::class];
    }

    /**
     * @param Return_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof Array_) {
            return null;
        }

        $optionsWithNodes = [];

        foreach ($this->options as $key => $value) {
            if ($key === 'provider' && is_string($value)) {
                // We explicitly map the options to convert specific strings into ClassConstFetch nodes
                // This ensures 'Vendor\MyClass' becomes \Vendor\MyClass::class in the output
                $optionsWithNodes[$key] = $this->nodeFactory->createClassConstReference($value);
            } else {
                $optionsWithNodes[$key] = $value;
            }
        }

        $node->expr->items[] = new ArrayItem(
            $this->nodeFactory->createArray($optionsWithNodes),
            new String_($this->identifier),
            false
        );

        return $node;
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $identifier = $configuration[self::IDENTIFIER] ?? '';
        $options = $configuration[self::OPTIONS] ?? [];

        Assert::stringNotEmpty($identifier);
        Assert::isArray($options);
        Assert::keyExists($options, 'provider');
        Assert::keyExists($options, 'source');

        $this->identifier = $identifier;
        $this->options = $options;
    }
}
