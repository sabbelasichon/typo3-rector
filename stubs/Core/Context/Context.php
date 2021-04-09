<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Context;

if (class_exists(Context::class)) {
    return;
}

final class Context
{
    public function getPropertyFromAspect(string $name, string $property, $default = null): void
    {

    }

    public function setAspect(string $name, AspectInterface $aspect): void
    {

    }
}
