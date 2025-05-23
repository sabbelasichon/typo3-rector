<?php

namespace TYPO3\CMS\Core\Messaging;

if (class_exists('TYPO3\CMS\Core\Messaging\FlashMessage')) {
    return;
}

class FlashMessage extends AbstractMessage
{
    public function jsonSerialize()
    {
        return [];
    }
}
