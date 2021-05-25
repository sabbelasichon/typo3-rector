<?php

namespace TYPO3\CMS\Form\Domain\Finishers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

if (class_exists('TYPO3\CMS\Form\Domain\Finishers\EmailFinisher')) {
    return;
}

class EmailFinisher
{
    /**
     * @var string
     */
    const FORMAT_PLAINTEXT = 'plaintext';

    /**
     * @var string
     */
    const FORMAT_HTML = 'html';

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @return void
     */
    protected function executeInternal()
    {

    }

    /**
     * @return void
     * @param string $optionName
     */
    public function setOption($optionName, $optionValue)
    {
        $optionName = (string) $optionName;
        $this->options[$optionName] = $optionValue;
    }

    /**
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

}
