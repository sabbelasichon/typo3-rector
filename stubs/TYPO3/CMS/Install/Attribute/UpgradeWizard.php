<?php

namespace TYPO3\CMS\Install\Attribute;

if (class_exists('TYPO3\CMS\Install\Attribute\UpgradeWizard')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS)]
class UpgradeWizard
{
}
