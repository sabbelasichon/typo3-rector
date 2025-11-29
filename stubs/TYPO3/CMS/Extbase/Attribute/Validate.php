<?php

namespace TYPO3\CMS\Extbase\Attribute;

if (class_exists('TYPO3\CMS\Extbase\Attribute\Validate')) {
    return;
}

class Validate
{
    public function __construct(
        $validator,
        array $options = [],
        ?string $param = null
    ) {
    }
}
