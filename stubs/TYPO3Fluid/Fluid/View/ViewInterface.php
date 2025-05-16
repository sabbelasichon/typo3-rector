<?php

namespace TYPO3Fluid\Fluid\View;

if (interface_exists('TYPO3Fluid\Fluid\View\ViewInterface')) {
    return;
}

interface ViewInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function assign($key, $value);

    /**
     * @return void
     */
    public function assignMultiple(array $values);

    /**
     * @return string
     */
    public function render();

    /**
     * Renders a given section.
     *
     * @param string $sectionName Name of section to render
     * @param array $variables The variables to use
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string rendered template for the section
     */
    public function renderSection($sectionName, array $variables = [], $ignoreUnknown = false);

    /**
     * Renders a partial.
     *
     * @param string $partialName
     * @param string $sectionName
     * @param array $variables
     * @param bool $ignoreUnknown Ignore an unknown section and just return an empty string
     * @return string
     */
    public function renderPartial($partialName, $sectionName, array $variables, $ignoreUnknown = false);
}
