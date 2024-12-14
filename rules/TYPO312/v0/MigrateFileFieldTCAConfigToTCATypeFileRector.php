<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-98479-DeprecatedFileReferenceRelatedFunctionality.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFileFieldTCAConfigToTCATypeFileRector\MigrateFileFieldTCAConfigToTCATypeFileRectorTest
 */
final class MigrateFileFieldTCAConfigToTCATypeFileRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Migrate method ExtensionManagementUtility::getFileFieldTCAConfig() to TCA type file',
            [new CodeSample(
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'image_field' => [
            'config' => ExtensionManagementUtility::getFileFieldTCAConfig(
                'logo',
                [
                    'maxitems' => 1,
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                        'fileUploadAllowed' => 0
                    ],
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                            AbstractFile::FILETYPE_IMAGE => [
                                'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
            ),
        ],
    ],
];
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
return [
    'columns' => [
        'image_field' => [
            'config' => [
                'type' => 'file',
                'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference',
                    'fileUploadAllowed' => 0
                ],
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                        AbstractFile::FILETYPE_IMAGE => [
                            'showitem' => '
                            --palette--;LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
CODE_SAMPLE
            )]
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

        $fileFieldTCAConfigArray = new Array_();
        $fileFieldTCAConfigArray->items[] = new ArrayItem(new String_('file'), new String_('type'), false, [
            AttributeKey::COMMENTS => [new Comment('### !!! Watch out for fieldName different from columnName')],
        ]);

        if (isset($node->args[2])) {
            $fileFieldTCAConfigArray->items[] = new ArrayItem($node->args[2]->value, new String_('allowed'));
        }

        if (isset($node->args[3])) {
            $fileFieldTCAConfigArray->items[] = new ArrayItem($node->args[3]->value, new String_('disallowed'));
        }

        if (isset($node->args[1]) && $node->args[1]->value instanceof Array_) {
            foreach ($node->args[1]->value->items ?? [] as $item) {
                $fileFieldTCAConfigArray->items[] = $item;
            }
        }

        return $fileFieldTCAConfigArray;
    }

    private function shouldSkip(StaticCall $node): bool
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Utility\ExtensionManagementUtility')
        )) {
            return true;
        }

        return ! $this->isName($node->name, 'getFileFieldTCAConfig');
    }
}
