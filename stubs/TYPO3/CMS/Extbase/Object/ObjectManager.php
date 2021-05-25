<?php

namespace TYPO3\CMS\Extbase\Object;

if (class_exists('TYPO3\CMS\Extbase\Object\ObjectManager')) {
    return;
}

class ObjectManager implements ObjectManagerInterface
{
    /**
     * @param $objectName
     *
     * @return object
     */
    public function get($objectName)
    {
        return new $objectName(func_get_args());
    }
}
