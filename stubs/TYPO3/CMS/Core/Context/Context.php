<?php

namespace TYPO3\CMS\Core\Context;

if (class_exists('TYPO3\CMS\Core\Context\Context')) {
    return;
}

class Context
{
    /**
     * @return void
     * @param string $name
     * @param string $property
     */
    public function getPropertyFromAspect($name, $property, $default = null)
    {
    }

    /**
     * @return void
     * @param string $name
     */
    public function setAspect($name, AspectInterface $aspect)
    {
    }

    /**
     * @param string $name
     * @return AspectInterface
     */
    public function getAspect(string $name): AspectInterface
    {
        return new DateTimeAspect();
    }
}
