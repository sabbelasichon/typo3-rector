<?php
declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Plugin;

if(class_exists(AbstractPlugin::class)) {
    return;
}

class AbstractPlugin
{
    public function pi_getLL($key, $alternativeLabel = '', $hsc = false): void
    {
    }
}
