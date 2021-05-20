<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Context;

if (class_exists('TYPO3\CMS\Core\Context\AspectInterface')) {
    return;
}

interface AspectInterface
{
    public function get(string $name);
}
