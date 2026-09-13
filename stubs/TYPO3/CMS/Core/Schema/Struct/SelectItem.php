<?php

declare(strict_types=1);

namespace TYPO3\CMS\Core\Schema\Struct;

if (class_exists('TYPO3\CMS\Core\Schema\Struct\SelectItem')) {
    return;
}

final class SelectItem
{
    /**
     * @param string $type
     * @param string $label
     * @param int|string|null $value
     * @param string|null $icon
     * @param string|null $group
     * @param string|array|null $description
     * @param bool $invertStateDisplay
     * @param string|null $iconIdentifierChecked
     * @param string|null $iconIdentifierUnchecked
     * @param string|null $labelChecked
     * @param string|null $labelUnchecked
     * @param string|null $iconOverlay
     */
    public function __construct(
        string $type,
        string $label,
        $value,
        ?string $icon = null,
        ?string $group = null,
        $description = null,
        bool $invertStateDisplay = false,
        ?string $iconIdentifierChecked = null,
        ?string $iconIdentifierUnchecked = null,
        ?string $labelChecked = null,
        ?string $labelUnchecked = null,
        ?string $iconOverlay = null,
    ) {}
}
