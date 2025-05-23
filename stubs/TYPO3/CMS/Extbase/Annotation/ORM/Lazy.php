<?php

namespace TYPO3\CMS\Extbase\Annotation\ORM;

if (class_exists('TYPO3\CMS\Extbase\Annotation\ORM\Lazy')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Lazy
{
}
