<?php

namespace TYPO3\CMS\Core\Attribute;


if (class_exists('TYPO3\CMS\Core\Attribute\AsEventListener')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class AsEventListener
{
    public function __construct(
        public ?string $identifier = null,
        public ?string $event = null,
        public ?string $method = null,
        public ?string $before = null,
        public ?string $after = null,
    ) {}
}
