<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Context;

if (class_exists(AspectInterface::class)) {
    return;
}

interface AspectInterface
{
    public function get(string $name);
}
