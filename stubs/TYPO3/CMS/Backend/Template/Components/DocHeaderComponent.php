<?php

namespace TYPO3\CMS\Backend\Template\Components;

use TYPO3\CMS\Core\Resource\ResourceInterface;

if (class_exists('TYPO3\CMS\Backend\Template\Components\DocHeaderComponent')) {
    return;
}

class DocHeaderComponent
{
    public function setMetaInformation(array $metaInformation)
    {
    }

    public function setMetaInformationForResource(ResourceInterface $resource): void
    {
    }

    public function setPageBreadcrumb(array $pageRecord): void
    {
    }

    public function setResourceBreadcrumb(ResourceInterface $resource): void
    {
    }

    public function getButtonBar(): ButtonBar
    {
    }

    public function getMenuRegistry(): MenuRegistry
    {
    }

    public function setShortcutContext(
        string $routeIdentifier,
        string $displayName,
        array $arguments = []
    ): void {
    }
}
