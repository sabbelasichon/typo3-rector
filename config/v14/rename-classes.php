<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameClassRector::class, [
            'TYPO3\CMS\Install\Attribute\UpgradeWizard' => 'TYPO3\CMS\Core\Attribute\UpgradeWizard',
            'TYPO3\CMS\Install\Updates\ChattyInterface' => 'TYPO3\CMS\Core\Upgrades\ChattyInterface',
            'TYPO3\CMS\Install\Updates\ConfirmableInterface' => 'TYPO3\CMS\Core\Upgrades\ConfirmableInterface',
            'TYPO3\CMS\Install\Updates\RepeatableInterface' => 'TYPO3\CMS\Core\Upgrades\RepeatableInterface',
            'TYPO3\CMS\Install\Updates\UpgradeWizardInterface' => 'TYPO3\CMS\Core\Upgrades\UpgradeWizardInterface',
            'TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate' => 'TYPO3\CMS\Core\Upgrades\AbstractListTypeToCTypeUpdate',
            'TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite' => 'TYPO3\CMS\Core\Upgrades\DatabaseUpdatedPrerequisite',
            'TYPO3\CMS\Install\Updates\ReferenceIndexUpdatedPrerequisite' => 'TYPO3\CMS\Core\Upgrades\ReferenceIndexUpdatedPrerequisite',
        ]);
};
