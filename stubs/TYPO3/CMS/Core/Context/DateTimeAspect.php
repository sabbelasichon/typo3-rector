<?php
namespace TYPO3\CMS\Core\Context;

if (class_exists('TYPO3\CMS\Core\Context\DateTimeAspect')) {
    return;
}

class DateTimeAspect implements AspectInterface
{
    public function get($name)
    {
    }
}
