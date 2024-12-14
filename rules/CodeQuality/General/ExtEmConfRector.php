<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\CodeQuality\General;

use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ExtensionArchitecture/FileStructure/ExtEmconf.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\ExtEmConfRector\ExtEmConfRectorTest
 */
final class ExtEmConfRector extends AbstractRector implements ConfigurableRectorInterface, DocumentedRuleInterface
{
    /**
     * @var string
     */
    public const PHP_VERSION_CONSTRAINT = 'php_version_constraint';

    public const TYPO3_VERSION_CONSTRAINT = 'typo3_version_constraint';

    /**
     * @var string
     */
    public const ADDITIONAL_VALUES_TO_BE_REMOVED = 'additional_values_to_be_removed';

    private string $targetPHPVersionConstraint = '';

    private string $targetTypo3VersionConstraint = '';

    /**
     * @var string[]
     */
    private array $valuesToBeRemoved = [
        '_md5_values_when_last_written',

        // https://docs.typo3.org/m/typo3/reference-coreapi/8.7/en-us/ExtensionArchitecture/DeclarationFile/Index.html#deprecated-configuration
        'dependencies',
        'conflicts',
        'suggests',
        'docPath',
        'CGLcompliance',
        'CGLcompliance_note',
        'private',
        'download_password',
        'shy',
        'loadOrder',
        'priority',
        'internal',
        'modify_tables',
        'module',
        'lockType',
        'TYPO3_version',
        'PHP_version',

        // https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/ExtensionArchitecture/DeclarationFile/Index.html#deprecated-configuration
        'createDirs', // Deprecated since version 9.5
        'uploadfolder', // Deprecated since version 9.5

        // https://docs.typo3.org/m/typo3/reference-coreapi/12.4/en-us/ExtensionArchitecture/FileStructure/ExtEmconf.html
        'clearCacheOnLoad', // Deprecated since version 12.1
    ];

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof ArrayDimFetch) {
            return null;
        }

        if (! $this->isName($node->var->var, 'EM_CONF')) {
            return null;
        }

        if (! $node->var->dim instanceof Expr) {
            return null;
        }

        if (! $node->expr instanceof Array_) {
            return null;
        }

        if ($node->expr->items === []) {
            return null;
        }

        $nodeHasChanged = false;
        foreach ($node->expr->items as $itemKey => $item) {
            /** @var ArrayItem $item */
            if (! $item->key instanceof Expr) {
                continue;
            }

            if ($this->propertyCanBeRemoved($item)) {
                unset($node->expr->items[$itemKey]);

                $nodeHasChanged = true;

                continue;
            }

            if ($this->targetTypo3VersionConstraint === '') {
                continue;
            }

            if (! $this->valueResolver->isValue($item->key, 'constraints')) {
                continue;
            }

            if (! $item->value instanceof Array_) {
                continue;
            }

            if ($item->value->items === []) {
                continue;
            }

            foreach ($item->value->items as $constraintItem) {
                /** @var ArrayItem $constraintItem */
                if (! $constraintItem->key instanceof Expr) {
                    continue;
                }

                if (! $this->valueResolver->isValue($constraintItem->key, 'depends')) {
                    continue;
                }

                if (! $constraintItem->value instanceof Array_) {
                    continue;
                }

                if ($constraintItem->value->items === []) {
                    continue;
                }

                foreach ($constraintItem->value->items as $dependsItem) {
                    /** @var ArrayItem $dependsItem */
                    if (! $dependsItem->key instanceof Expr) {
                        continue;
                    }

                    if ($this->valueResolver->isValue($dependsItem->key, 'php')) {
                        $dependsItem->value = new String_($this->targetPHPVersionConstraint);

                        $nodeHasChanged = true;
                    }

                    if ($this->valueResolver->isValue($dependsItem->key, 'typo3')) {
                        $dependsItem->value = new String_($this->targetTypo3VersionConstraint);

                        $nodeHasChanged = true;
                    }
                }
            }
        }

        return $nodeHasChanged ? $node : null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor file ext_emconf.php', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'shy' => 0,
    'version' => '2.0.1',
    'dependencies' => '',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 0,
    'lockType' => '',
    'author' => 'Max Mustermann',
    'author_email' => 'max.mustermann@mustermann.de',
    'author_company' => 'Mustermann GmbH',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '7.6.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Foo\\Bar\\' => 'Classes/',
        ],
    ],
    '_md5_values_when_last_written' => 'a:0:{}',
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'version' => '2.0.1',
    'state' => 'stable',
    'author' => 'Max Mustermann',
    'author_email' => 'max.mustermann@mustermann.de',
    'author_company' => 'Mustermann GmbH',
    'constraints' => [
        'depends' => [
            'php' => '5.6.0-0.0.0',
            'typo3' => '7.6.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'Foo\\Bar\\' => 'Classes/',
        ],
    ],
];
CODE_SAMPLE
                ,
                [
                    self::PHP_VERSION_CONSTRAINT => '7.4.0-8.2.99',
                    self::TYPO3_VERSION_CONSTRAINT => '11.5.0-12.4.99',
                    self::ADDITIONAL_VALUES_TO_BE_REMOVED => ['clearCacheOnLoad'],
                ]
            ),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $additionalValuesToBeRemoved = $configuration[self::ADDITIONAL_VALUES_TO_BE_REMOVED] ?? [];
        $this->valuesToBeRemoved = array_merge($this->valuesToBeRemoved, $additionalValuesToBeRemoved);
        $this->targetPHPVersionConstraint = isset($configuration[self::PHP_VERSION_CONSTRAINT]) ? (string) $configuration[self::PHP_VERSION_CONSTRAINT] : '';
        $this->targetTypo3VersionConstraint = isset($configuration[self::TYPO3_VERSION_CONSTRAINT]) ? (string) $configuration[self::TYPO3_VERSION_CONSTRAINT] : '';
    }

    private function propertyCanBeRemoved(ArrayItem $item): bool
    {
        if (! $item->key instanceof Expr) {
            return false;
        }

        return $this->valueResolver->isValues($item->key, $this->valuesToBeRemoved);
    }
}
