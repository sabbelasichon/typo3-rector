<?php
declare(strict_types=1);

namespace TYPO3\CMS\Backend\Attribute;

if (class_exists('TYPO3\CMS\Backend\Backend\Attribute\Controller')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Controller
{
    public const TAG_NAME = 'backend.controller';

    public function __construct() {}
}
