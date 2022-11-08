<?php

namespace TYPO3\CMS\Extbase\Mvc\Controller;

use TYPO3\CMS\Extbase\Mvc\Cli\ConsoleOutput;

if (class_exists('TYPO3\CMS\Extbase\Mvc\Controller\CommandController')) {
    return;
}

class CommandController
{
    /**
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * @return void
     */
    protected function getBackendUserAuthentication()
    {
    }

    /**
     * Outputs specified text to the console window and appends a line break
     *
     * @param string $text Text to output
     * @param array $arguments Optional arguments to use for sprintf
     * @see output()
     */
    protected function outputLine($text = '', array $arguments = [])
    {
        $this->output->outputLine($text, $arguments);
    }
}
