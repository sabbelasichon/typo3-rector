<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Annotation\ORM;

if (class_exists('TYPO3\CMS\Extbase\Annotation\ORM\Transient')) {
    return;
}

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class Transient
{

}
