<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\RemoveFormatConstantsEmailFinisherRector\Fixture;

use TYPO3\CMS\Form\Domain\Finishers\EmailFinisher;

final class DownloadFormEmailFinisher extends EmailFinisher
{
    /**
     * @var string
     */
    private const FORMAT = 'format';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'format' => self::FORMAT_HTML,
    ];

    protected function executeInternal(): void
    {
        $this->setOption(self::FORMAT, EmailFinisher::FORMAT_HTML);
        $this->setOption('format', EmailFinisher::FORMAT_PLAINTEXT);
        $this->setOptions(['format' => self::FORMAT_HTML]);
        $this->options['format'] = self::FORMAT_PLAINTEXT;
        if ($this->options['format'] === self::FORMAT_HTML) {
            // do something if true
        }
        if ($this->options['format'] === self::FORMAT_PLAINTEXT) {
            // do something if false
        }
        if (self::FORMAT_PLAINTEXT === $this->options['format']) {
            // do something if false
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v4\RemoveFormatConstantsEmailFinisherRector\Fixture;

use TYPO3\CMS\Form\Domain\Finishers\EmailFinisher;

final class DownloadFormEmailFinisher extends EmailFinisher
{
    /**
     * @var string
     */
    private const FORMAT = 'format';

    /**
     * @var array
     */
    protected $defaultOptions = [
        'addHtmlPart' => true,
    ];

    protected function executeInternal(): void
    {
        $this->setOption('addHtmlPart', true);
        $this->setOption('addHtmlPart', false);
        $this->setOptions(['addHtmlPart' => true]);
        $this->options['addHtmlPart'] = false;
        if ($this->options['addHtmlPart'] === true) {
            // do something if true
        }
        if ($this->options['addHtmlPart'] === false) {
            // do something if false
        }
        if (false === $this->options['addHtmlPart']) {
            // do something if false
        }
    }
}

?>
