<?php
declare(strict_types=1);

namespace TYPO3\CMS\Frontend\Typolink;

if (interface_exists('TYPO3\CMS\Frontend\Typolink\LinkResultInterface')) {
    return;
}

interface LinkResultInterface
{
    public function getUrl(): string;

    public function getType(): string;

    public function getTarget(): string;

    public function getLinkConfiguration(): array;

    public function getLinkText(): ?string;

    public function withLinkText(string $linkText): self;

    public function withTarget(string $target): self;

    public function withAttributes(array $additionalAttributes, bool $resetExistingAttributes = false): self;
    public function withAttribute(string $attributeName, ?string $attributeValue): self;
    public function hasAttribute(string $attributeName): bool;
    public function getAttribute(string $attributeName): ?string;
    public function getAttributes(): array;
}
