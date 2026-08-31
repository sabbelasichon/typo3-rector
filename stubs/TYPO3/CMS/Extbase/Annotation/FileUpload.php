<?php

namespace TYPO3\CMS\Extbase\Annotation;

if (class_exists('TYPO3\CMS\Extbase\Annotation\FileUpload')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class FileUpload
{
}
