<?php

namespace TYPO3\CMS\Extbase\Annotation;

if (class_exists('TYPO3\CMS\Extbase\Annotation\IgnoreValidation')) {
    return;
}

/**
 * @Annotation
 * @Target({"METHOD"})
 */
#[\Attribute(\Attribute::TARGET_METHOD|\Attribute::IS_REPEATABLE)]
final class IgnoreValidation
{
}
