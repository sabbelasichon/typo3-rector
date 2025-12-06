<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO314\v0;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/14.0/Deprecation-106947-MoveUpgradeWizardRelatedInterfacesAndAttributeToEXTcore.html
 * @see \Ssch\TYPO3Rector\Tests\Rector\v14\v0\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector\MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRectorTest
 */
final class MoveUpgradeWizardRelatedInterfacesAndAttributesToCoreRector extends AbstractRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move upgrade wizard related interfaces and attribute to EXT:core', [new CodeSample(
            <<<'CODE_SAMPLE'
<?php

namespace MyVendor\MyExtension\Upgrades;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\RepeatableInterface;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard extends UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
  // ...
}
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
<?php

namespace MyVendor\MyExtension\Upgrades;

use TYPO3\CMS\Core\Attribute\UpgradeWizard;
use TYPO3\CMS\Core\Upgrades\ChattyInterface;
use TYPO3\CMS\Core\Upgrades\ConfirmableInterface;
use TYPO3\CMS\Core\Upgrades\RepeatableInterface;
use TYPO3\CMS\Core\Upgrades\UpgradeWizardInterface;

#[UpgradeWizard('myExtensionCustomUpgradeWizardIdentifier')]
class CustomUpgradeWizard extends UpgradeWizardInterface, ChattyInterface, RepeatableInterface
{
  // ...
}
CODE_SAMPLE
        )]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [];
    }

    public function refactor(Node $node): ?Node
    {
        return null;
    }
}
