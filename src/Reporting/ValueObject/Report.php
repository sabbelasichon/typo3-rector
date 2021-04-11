<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Reporting\ValueObject;

use Rector\Core\Contract\Rector\RectorInterface;
use Symplify\SmartFileSystem\SmartFileInfo;

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
     * @var SmartFileInfo
     */
    private $smartFileInfo;

    /**
     * @var string[]
     */
    private $suggestions = [];

    /**
     * @param string[] $suggestions
     */
    public function __construct(
        string $message,
        RectorInterface $rector,
        SmartFileInfo $smartFileInfo,
        array $suggestions = [])
    {
        $this->message = $message;
        $this->rector = $rector;
        $this->smartFileInfo = $smartFileInfo;
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

    public function getSmartFileInfo(): SmartFileInfo
    {
        return $this->smartFileInfo;
    }

    public function getSuggestions(): array
    {
        return $this->suggestions;
    }
}
