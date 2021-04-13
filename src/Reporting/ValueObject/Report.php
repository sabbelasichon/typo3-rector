<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting\ValueObject;

use Rector\Core\Contract\Rector\RectorInterface;

final class Report
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var RectorInterface
     */
    private $rector;

    /**
     * @var string[]
     */
    private $suggestions = [];

    /**
     * @param string[] $suggestions
     */
    public function __construct(string $message, RectorInterface $rector, array $suggestions = [])
    {
        $this->message = $message;
        $this->rector = $rector;
        $this->suggestions = $suggestions;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRector(): RectorInterface
    {
        return $this->rector;
    }

    public function getSuggestions(): array
    {
        return $this->suggestions;
    }
}
