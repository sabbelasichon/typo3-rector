<?php
declare(strict_types=1);


namespace TYPO3\CMS\Extbase\Http;

use Psr\Http\Message\ResponseInterface;

if (class_exists(ForwardResponse::class)) {
    return;
}

final class ForwardResponse implements ResponseInterface
{
    /**
     * @var string
     */
    private $actionName;

    /**
     * @var string|null
     */
    private $controllerName = null;

    /**
     * @var string|null
     */
    private $extensionName = null;

    /**
     * @var array
     */
    private $arguments = [];

    public function __construct(string $actionName)
    {
        $this->actionName = $actionName;
    }

    public function withControllerName(string $controllerName): self
    {
        $clone = clone $this;
        $clone->controllerName = $controllerName;

        return $clone;
    }

    public function withoutControllerName(): self
    {
        $clone = clone $this;
        $clone->controllerName = null;

        return $clone;
    }

    public function withExtensionName(string $extensionName): self
    {
        $clone = clone $this;
        $clone->extensionName = $extensionName;

        return $clone;
    }

    public function withoutExtensionName(): self
    {
        $clone = clone $this;
        $this->extensionName = null;

        return $clone;
    }

    public function withArguments(array $arguments): self
    {
        $clone = clone $this;
        $clone->arguments = $arguments;

        return $clone;
    }

    public function withoutArguments(): self
    {
        $clone = clone $this;
        $this->arguments = [];

        return $clone;
    }

    public function withStatus(string $code, string $reasonPhrase = ''): ResponseInterface
    {
    }
}
