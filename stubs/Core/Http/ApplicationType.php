<?php
declare(strict_types=1);


namespace TYPO3\CMS\Core\Http;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;

if(class_exists(ApplicationType::class)) {
    return;
}

final class ApplicationType
{

    private function __construct(string $type)
    {
    }

    public static function fromRequest(ServerRequestInterface $request): self
    {
        return new self('foo');
    }

    public function isFrontend(): bool
    {
        return true;
    }

    public function isBackend(): bool
    {
        return true;
    }
}
