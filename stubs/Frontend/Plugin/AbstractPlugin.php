<?php
declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Plugin;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

if(class_exists(AbstractPlugin::class)) {
    return;
}

class AbstractPlugin
{
    /**
     * The backReference to the mother cObj object set at call time
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    public function pi_getLL($key, $alternativeLabel = '', $hsc = false): void
    {
    }
}
