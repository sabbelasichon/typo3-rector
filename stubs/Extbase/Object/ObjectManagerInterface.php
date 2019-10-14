<?php

namespace TYPO3\CMS\Extbase\Object;

interface ObjectManagerInterface
{
    /**
     * @param $objectName
     *
     * @return object
     */
    public function get($objectName);
}
