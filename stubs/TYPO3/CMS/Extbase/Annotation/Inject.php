<?php
declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Annotation;

if (class_exists('TYPO3\CMS\Extbase\Annotation\Inject')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Inject
{
}
