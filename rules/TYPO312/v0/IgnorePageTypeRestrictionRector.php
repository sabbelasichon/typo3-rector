<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO312\v0;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Ssch\TYPO3Rector\Rector\AbstractTcaRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Deprecation-98487-ExtensionManagementUtilityallowTableOnStandardPages.html
 */
final class IgnorePageTypeRestrictionRector extends AbstractTcaRector implements ConfigurableRectorInterface
{
    public const TABLE_CONFIGURATION = 'table-configuration';

    private ?string $table = null;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Move method ExtensionManagementUtility::allowTableOnStandardPages to TCA configuration',
            [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
ExtensionManagementUtility::allowTableOnStandardPages('my_table');
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
$GLOBALS['TCA']['my_table']['ctrl']['security']['ignorePageTypeRestriction']
CODE_SAMPLE
                , [
                    self::TABLE_CONFIGURATION => 'my_table',
                ])]
        );
    }

    public function configure(array $configuration): void
    {
        $table = $configuration[self::TABLE_CONFIGURATION] ?? null;
        Assert::nullOrString($table);

        $this->table = $table;
    }

    protected function refactorCtrl(Array_ $ctrlArray): void
    {
        if ($this->table === null) {
            return;
        }

        $securityArray = $this->extractSubArrayByKey($ctrlArray, 'security');

        if ($securityArray instanceof Array_ && $this->hasKey($securityArray, 'ignorePageTypeRestriction')) {
            return;
        }

        if ($securityArray instanceof Array_) {
            $securityArray->items[] = new ArrayItem($this->nodeFactory->createTrue(), new String_(
                'ignorePageTypeRestriction'
            ));
        } else {
            $ctrlArray->items[] = new ArrayItem($this->nodeFactory->createArray([
                'ignorePageTypeRestriction' => true,
            ]), new String_('security'));
        }
    }
}
