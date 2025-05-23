<?php

namespace TYPO3\CMS\Frontend\Typolink;

if (class_exists('TYPO3\CMS\Frontend\Typolink\EmailLinkBuilder')) {
    return;
}

class EmailLinkBuilder
{
    /**
     * @return void
     */
    public function processEmailLink(string $mailAddress, string $linkText)
    {
    }
}
