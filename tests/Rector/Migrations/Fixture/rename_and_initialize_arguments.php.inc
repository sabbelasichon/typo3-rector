<?php

class MySpecialViewHelperWithFullQualifiedNamespaceLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{
    /**
     * @var MySpecialViewHelperWithFullQualifiedNamespaceLinkViewHelper
     * @inject
     */
    protected $pluginSettingsService;

    public function initializeArguments()
    {
    }

    /**
     * Render link to adebisevent item
     * @param array $settings In case you want to override the general TS settings especially for current link
     * @param bool $uriOnly return only the url without the a-tag
     * @param array $configuration optional typolink configuration
     *
     * @return string link
     */
    public function render(
        array $settings = [],
        $uriOnly = false,
        $configuration = []
    ) {
        return 'foo';
    }
}

?>
-----
<?php

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
class MySpecialViewHelperWithFullQualifiedNamespaceLinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var MySpecialViewHelperWithFullQualifiedNamespaceLinkViewHelper
     */
    protected $pluginSettingsService;

    public function initializeArguments()
    {
        $this->registerArgument('settings', 'array', 'In case you want to override the general TS settings especially for current link', false, []);
        $this->registerArgument('uriOnly', 'bool', 'return only the url without the a-tag', false, false);
        $this->registerArgument('configuration', 'array', 'optional typolink configuration', false, []);
    }

    /**
     * Render link to adebisevent item
     *
     * @return string link
     */
    public function render()
    {
        $settings = $this->arguments['settings'];
        $uriOnly = $this->arguments['uriOnly'];
        $configuration = $this->arguments['configuration'];
        return 'foo';
    }

    public function injectPluginSettingsService(MySpecialViewHelperWithFullQualifiedNamespaceLinkViewHelper $pluginSettingsService): void
    {
        $this->pluginSettingsService = $pluginSettingsService;
    }
}
