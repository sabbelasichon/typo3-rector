<?php

namespace TYPO3\CMS\Install\Updates;

if (interface_exists('TYPO3\CMS\Install\Updates\UpgradeWizardInterface')) {
    return;
}

interface UpgradeWizardInterface
{
}
