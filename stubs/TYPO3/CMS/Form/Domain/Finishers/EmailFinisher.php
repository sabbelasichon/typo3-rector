<?php

declare(strict_types=1);

namespace TYPO3\CMS\Form\Domain\Finishers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

if (class_exists(EmailFinisher::class)) {
    return;
}

class EmailFinisher
{
    /**
     * @var string
     */
    public const FORMAT_PLAINTEXT = 'plaintext';

    /**
     * @var string
     */
    public const FORMAT_HTML = 'html';

    /**
     * @var array
     */
    protected $options = [];

    protected function executeInternal(): void
    {

    }

    public function setOption(string $optionName, $optionValue): void
    {
        $this->options[$optionName] = $optionValue;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

}
