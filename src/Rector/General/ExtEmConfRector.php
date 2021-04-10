<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\General;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ExtensionArchitecture/DeclarationFile/Index.html
 */
final class ExtEmConfRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TYPO3_VERSION_CONSTRAINT = 'typo3_version_constraint';

    /**
     * @var string
     */
    public const ADDITIONAL_VALUES_TO_BE_REMOVED = 'additional_values_to_be_removed';

    /**
     * @var string[]
     */
    private const PROPERTIES_TO_BOOLEAN = ['clearCacheOnLoad', 'uploadfolder'];

    /**
     * @var string
     */
    private $targetTypo3VersionConstraint = '';

    /**
     * @var string[]
     */
    private $valuesToBeRemoved = [
        'dependencies',
        'conflicts',
        'suggests',
        'private',
        'download_password',
        'TYPO3_version',
        'PHP_version',
        'internal',
        'module',
        'loadOrder',
        'lockType',
        'shy',
        'priority',
        'modify_tables',
        'CGLcompliance',
        'CGLcompliance_note',
    ];

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

        if (null === $node->var->dim) {
            return null;
        }

        if (! $this->isName($node->var->dim, '_EXTKEY')) {
            return null;
        }

        if (! $node->expr instanceof Array_) {
            return null;
        }

        if ([] === $node->expr->items || null === $node->expr->items) {
            return null;
        }

        $nodeHasChanged = false;
        foreach ($node->expr->items as $item) {
            /** @var ArrayItem $item */
            if (null === $item->key) {
                continue;
            }

            if ($this->propertyFixString($item)) {
                $item->key = new String_('clearCacheOnLoad');

                $nodeHasChanged = true;
            }

            if ($this->propertyCanBeRemoved($item)) {
                $this->removeNode($item);

                $nodeHasChanged = true;

                continue;
            }

            if ($this->valueResolver->isValues($item->key, self::PROPERTIES_TO_BOOLEAN)) {
                $nodeHasChanged = true;

                if (! (bool) $this->valueResolver->getValue($item->value)) {
                    $this->removeNode($item);

                    continue;
                }

                $item->value = $this->nodeFactory->createTrue();
            }

            if ('' === $this->targetTypo3VersionConstraint) {
                continue;
            }

            if (! $this->valueResolver->isValue($item->key, 'constraints')) {
                continue;
            }

            if (! $item->value instanceof Array_) {
                continue;
            }

            if (null === $item->value->items) {
                continue;
            }

            foreach ($item->value->items as $constraintItem) {
                /** @var ArrayItem $constraintItem */
                if (null === $constraintItem->key) {
                    continue;
                }

                if (! $this->valueResolver->isValue($constraintItem->key, 'depends')) {
                    continue;
                }

                if (! $constraintItem->value instanceof Array_) {
                    continue;
                }

                if (null === $constraintItem->value->items) {
                    continue;
                }

                foreach ($constraintItem->value->items as $dependsItem) {
                    /** @var ArrayItem $dependsItem */
                    if (null === $dependsItem->key) {
                        continue;
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

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor file ext_emconf.php', [
            new ConfiguredCodeSample(<<<'CODE_SAMPLE'
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
    'author' => 'Max Mustrmann',
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
    'autoload' =>
        [
            'psr-4' =>
                [
                    'Foo\\Bar\\' => 'Classes/',
                ],
        ],
    '_md5_values_when_last_written' => 'a:0:{}',
];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
$EM_CONF[$_EXTKEY] = [
    'title' => 'Package Extension',
    'description' => 'Package Extension',
    'category' => 'fe',
    'version' => '2.0.1',
    'state' => 'stable',
    'author' => 'Max Mustrmann',
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
    'autoload' =>
        [
            'psr-4' =>
                [
                    'Foo\\Bar\\' => 'Classes/',
                ],
        ],
    '_md5_values_when_last_written' => 'a:0:{}',
];
CODE_SAMPLE
            , [
                self::ADDITIONAL_VALUES_TO_BE_REMOVED => ['createDirs', 'uploadfolder'],
            ]),
        ]);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $additionalValuesToBeRemoved = $configuration[self::ADDITIONAL_VALUES_TO_BE_REMOVED] ?? [];
        $this->valuesToBeRemoved = array_merge($this->valuesToBeRemoved, $additionalValuesToBeRemoved);
        $this->targetTypo3VersionConstraint = isset($configuration[self::TYPO3_VERSION_CONSTRAINT]) ? (string) $configuration[self::TYPO3_VERSION_CONSTRAINT] : '';
    }

    private function propertyCanBeRemoved(ArrayItem $item): bool
    {
        if (null === $item->key) {
            return false;
        }

        return $this->valueResolver->isValues($item->key, $this->valuesToBeRemoved);
    }

    private function propertyFixString(ArrayItem $item): bool
    {
        if (null === $item->key) {
            return false;
        }

        return $this->valueResolver->isValue($item->key, 'clearcacheonload');
    }
}
