<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Object;

if (class_exists(ObjectManagerInterface::class)) {
    return;
}

interface ObjectManagerInterface
{
    /**
     * @param $objectName
     *
     * @return object
     */
    public function get($objectName);
}
