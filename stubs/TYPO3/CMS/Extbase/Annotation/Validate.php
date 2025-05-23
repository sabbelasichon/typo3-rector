<?php

namespace TYPO3\CMS\Extbase\Annotation;

if (class_exists('TYPO3\CMS\Extbase\Annotation\Validate')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY|\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
final class Validate
{
}
