<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Ssch\TYPO3Rector\Helper\ArrayUtility;
use Ssch\TYPO3Rector\Helper\StringUtility;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97159-NewTCATypeLink.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateRenderTypeInputLinkToTypeLinkRector\MigrateRenderTypeInputLinkToTypeLinkRectorTest
 */
final class MigrateRenderTypeInputLinkToTypeLinkRector extends AbstractTcaRector implements ConfigurableRectorInterface
{
    public const ALLOWED_TYPES = 'allowedTypes';

    /**
     * @var array|string[]
     */
    private array $allowedTypes = ['page', 'file', 'folder', 'url', 'email', 'record', 'telephone'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('migrate renderType inputLink to new tca field type link', [new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [],
    'columns' => [
        'full_example' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'required' => true,
                'size' => 21,
                'max' => 1234,
                'eval' => 'trim,null',
                'fieldControl' => [
                    'linkPopup' => [
                        'disabled' => true,
                        'options' => [
                            'title' => 'Browser title',
                            'allowedExtensions' => 'jpg,png',
                            'blindLinkFields' => 'class,target,title',
                            'blindLinkOptions' => 'mail,folder,file,telephone',
                        ],
                    ],
                ],
                'softref' => 'typolink',
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
return [
    'ctrl' => [],
    'columns' => [
        'full_example' => [
            'config' => [
                'type' => 'link',
                'required' => true,
                'size' => 21,
                'eval' => 'null',
                'allowedTypes' => ['page', 'url', 'record'],
                'appearance' => [
                    'enableBrowser' => false,
                    'browserTitle' => 'Browser title',
                    'allowedOptions' => ['params', 'rel'],
                    'allowedFileExtensions' => ['jpg', 'png']
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            ,
            [
                self::ALLOWED_TYPES => ['page', 'file', 'folder', 'url', 'email', 'record', 'telephone'],
            ]
        )]);
    }

    public function configure(array $configuration): void
    {
        if ($configuration !== []
            && ! empty($configuration[self::ALLOWED_TYPES])
            && is_array($configuration[self::ALLOWED_TYPES])
        ) {
            $this->allowedTypes = $configuration[self::ALLOWED_TYPES];
        }
    }

    protected function refactorColumn(Expr $columnName, Expr $columnTca): void
    {
        $configArray = $this->extractSubArrayByKey($columnTca, self::CONFIG);
        if (! $configArray instanceof Array_) {
            return;
        }

        // Early return in case column is not of type=input
        if (! $this->isConfigType($configArray, 'input')) {
            return;
        }

        if (! $this->hasKey($configArray, 'renderType')) {
            return;
        }

        if (! $this->configIsOfRenderType($configArray, 'inputLink')) {
            return;
        }

        // Set the TCA type to "link"
        $this->changeTcaType($configArray, 'link');

        // Unset "renderType" and "max"
        $this->removeArrayItemFromArrayByKey($configArray, 'renderType');
        $this->removeArrayItemFromArrayByKey($configArray, 'max');

        // Unset "softref" if set to "typolink"
        if ($this->hasKeyValuePair($configArray, 'softref', 'typolink')) {
            $this->removeArrayItemFromArrayByKey($configArray, 'softref');
        }

        $this->refactorFieldControl($configArray);

        $evalArrayItem = $this->extractArrayItemByKey($configArray, 'eval');
        if (! $evalArrayItem instanceof ArrayItem) {
            return;
        }

        $evalListValue = $this->valueResolver->getValue($evalArrayItem->value);
        if (! is_string($evalListValue)) {
            return;
        }

        if (StringUtility::inList($evalListValue, 'null')) {
            $evalArrayItem->value = new String_('null');
        } else {
            // 'eval' is not null, remove whole configuration
            $this->removeArrayItemFromArrayByKey($configArray, 'eval');
        }

        $this->hasAstBeenChanged = true;
    }

    private function refactorFieldControl(Array_ $configArray): void
    {
        $fieldControl = $this->extractArrayValueByKey($configArray, 'fieldControl');
        $linkPopup = $this->extractArrayValueByKey($fieldControl, 'linkPopup');
        $popupOptions = $this->extractArrayValueByKey($linkPopup, 'options');
        if (
            ! $fieldControl instanceof Array_
            || ! $linkPopup instanceof Array_
        ) {
            return;
        }

        if ($popupOptions instanceof Array_) {
            $blindLinkOptions = $this->extractArrayValueByKey($popupOptions, 'blindLinkOptions');
            if ($blindLinkOptions instanceof String_) {
                $blindLinkOptionsValue = $this->valueResolver->getValue($blindLinkOptions);
                if (trim($blindLinkOptionsValue) !== '') {
                    $allowedTypes = array_values(array_diff(
                        $this->allowedTypes,
                        ArrayUtility::trimExplode(
                            ',',
                            str_replace('mail', 'email', (string) $blindLinkOptionsValue),
                            true
                        )
                    ));

                    $items = $this->nodeFactory->createArray($allowedTypes);
                    $configArray->items[] = new ArrayItem($items, new String_('allowedTypes'));
                }
            }
        }

        $appearances = [];
        if ($this->hasKeyValuePair($linkPopup, 'disabled', true)) {
            $appearances['enableBrowser'] = false;
        }

        if ($popupOptions instanceof Array_) {
            if ($this->hasKey($popupOptions, 'title')) {
                $title = $this->extractArrayValueByKey($popupOptions, 'title');
                if ($title instanceof String_) {
                    $titleValue = $this->valueResolver->getValue($title);
                    if (trim($titleValue) !== '' && trim($titleValue) !== '0') {
                        $appearances['browserTitle'] = $titleValue;
                    }
                }
            }

            if ($this->hasKey($popupOptions, 'blindLinkFields')) {
                $blindLinkFields = $this->extractArrayValueByKey($popupOptions, 'blindLinkFields');
                if ($blindLinkFields instanceof String_) {
                    $blindLinkFieldsValue = $this->valueResolver->getValue($blindLinkFields);
                    if (trim($blindLinkFieldsValue) !== '') {
                        $appearances['allowedOptions'] = array_values(array_diff(
                            ['target', 'title', 'class', 'params', 'rel'],
                            ArrayUtility::trimExplode(',', $blindLinkFieldsValue, true)
                        ));
                    }
                }
            }

            if ($this->hasKey($popupOptions, 'allowedExtensions')) {
                $allowedExtensions = $this->extractArrayValueByKey($popupOptions, 'allowedExtensions');
                if ($allowedExtensions instanceof String_) {
                    $allowedExtensionsValue = $this->valueResolver->getValue($allowedExtensions);
                    if (trim($allowedExtensionsValue) !== '') {
                        $appearances['allowedFileExtensions'] = ArrayUtility::trimExplode(
                            ',',
                            $allowedExtensionsValue,
                            true
                        );
                    }
                }
            }
        }

        if ($appearances !== []) {
            $configArray->items[] = new ArrayItem($this->nodeFactory->createArray($appearances), new String_(
                'appearance'
            ));
        }

        // Unset ['fieldControl']['linkPopup'] - Note: We do this here to ensure
        // also an invalid (e.g. not an array) field control configuration is removed.
        $this->removeArrayItemFromArrayByKey($fieldControl, 'linkPopup');

        // In case "linkPopup" has been the only configured fieldControl, unset ['fieldControl'], too.
        if ($fieldControl->items === []) {
            $this->removeArrayItemFromArrayByKey($configArray, 'fieldControl');
        }
    }
}
