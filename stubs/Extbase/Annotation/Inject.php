<?php
declare(strict_types = 1);

namespace TYPO3\CMS\Extbase\Annotation;

if (class_exists(Inject::class)) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Inject
{
}
