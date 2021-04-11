<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v5;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Ssch\TYPO3Rector\Helper\TcaHelperTrait;
use Ssch\TYPO3Rector\Reporting\Reporter;
use Ssch\TYPO3Rector\Reporting\ValueObject\Report;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.5/Deprecation-86406-TCATypeGroupInternal_typeFileAndFile_reference.html
 */
final class RefactorTypeInternalTypeFileAndFileReferenceToFalRector extends AbstractRector
{
    use TcaHelperTrait;

    /**
     * @var Reporter
     */
    private $reportLogger;

    public function __construct(Reporter $reporter)
    {
        $this->reportLogger = $reporter;
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
        if (! $this->isTca($node)) {
            return null;
        }

        $columns = $this->extractColumns($node);

        if (! $columns instanceof ArrayItem) {
            return null;
        }

        $columnItems = $columns->value;

        if (! $columnItems instanceof Array_) {
            return null;
        }

        $hasAstBeenChanged = false;
        foreach ($columnItems->items as $columnItem) {
            if (! $columnItem instanceof ArrayItem) {
                continue;
            }

            if (null === $columnItem->key) {
                continue;
            }

            if (! $columnItem->value instanceof Array_) {
                continue;
            }

            foreach ($columnItem->value->items as $configValue) {
                if (null === $configValue) {
                    continue;
                }

                if (null === $configValue->key) {
                    continue;
                }

                if (! $configValue->value instanceof Array_) {
                    continue;
                }

                if (! $this->valueResolver->isValue($configValue->key, 'config')) {
                    continue;
                }

                if (! $this->isConfigType($configValue->value, 'group')) {
                    continue;
                }

                if (! $this->configIsOfInternalType($configValue->value, 'file') &&
                     ! $this->configIsOfInternalType($configValue->value, 'file_reference')
                ) {
                    continue;
                }

                $newConfig = new Array_();
                $allowed = null;
                foreach ($configValue->value->items as $configItemValue) {
                    if (! $configItemValue instanceof ArrayItem) {
                        continue;
                    }

                    if (null === $configItemValue->key) {
                        continue;
                    }

                    if ($this->valueResolver->isValues(
                        $configItemValue->key,
                        ['max_size', 'uploadfolder', 'maxitems']
                    )) {
                        $newConfig->items[] = new ArrayItem($configItemValue->value, $configItemValue->key);
                        continue;
                    }

                    if ($this->valueResolver->isValue($configItemValue->key, 'allowed')) {
                        $allowed = $configItemValue->value;
                    }
                }

                $hasAstBeenChanged = true;

                $args = [$columnItem->key, $newConfig];

                if (null !== $allowed) {
                    $args[] = $allowed;
                }

                $configValue->value = $this->nodeFactory->createStaticCall(
                    ExtensionManagementUtility::class,
                    'getFileFieldTCAConfig',
                    $args
                );
            }
        }

        if ($hasAstBeenChanged) {
            $report = new Report('You have to do more', $this, $node->getAttribute(AttributeKey::FILE_INFO));
            $this->reportLogger->report($report);
        }

        return $hasAstBeenChanged ? $node : null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move TCA type group internal_type file and file_reference to FAL configuration', [
            new CodeSample(<<<'CODE_SAMPLE'
return [
            'ctrl' => [],
            'columns' => [
                'feedback_image' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => [
                        'type' => 'group',
                        'internal_type' => 'file',
                        'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                        'max_size' => '20000',
                        'uploadfolder' => 'fileadmin/feedbacks',
                        'maxitems' => '1',
                    ],
                ],
            ],
        ];
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
return [
            'ctrl' => [],
            'columns' => [
                'feedback_image' => [
                    'exclude' => 1,
                    'label' => 'FeedbackImage',
                    'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                        'feedback_image',
                        [
                            'max_size' => '20000',
                            'uploadfolder' => 'fileadmin/feedbacks',
                            'maxitems' => 1,
                            'appearance' => [
                                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                            ],
                        ],
                        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
                    ),
                ],
            ],
        ];
CODE_SAMPLE
            ),
        ]);
    }
}
