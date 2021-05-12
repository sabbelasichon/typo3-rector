<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html\Fixture;


use TYPO3\CMS\Core\Html\HtmlParser;

final class HtmlParserMarkerRendererMethods
{

    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $htmlparser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(HtmlParser::class);
        $template = $htmlparser->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = $htmlparser->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = $htmlparser->substituteSubpartArray($html2, []);

        $html3 = $htmlparser->processTag($value, $conf, $endTag, $protected = 0);
        $html4 = $htmlparser->processContent($value, $dir, $conf);

        $content = $htmlparser->substituteMarker($content, $marker, $markContent);
        $content .= $htmlparser->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= $htmlparser->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content = $htmlparser->XHTML_clean($content);
    }


}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\Core\Html\Fixture;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Html\HtmlParser;

final class HtmlParserMarkerRendererMethods
{

    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $htmlparser = GeneralUtility::makeInstance(HtmlParser::class);
        $template = $htmlparser->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = $htmlparser->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = $htmlparser->substituteSubpartArray($html2, []);

        $html3 = $htmlparser->processTag($value, $conf, $endTag, $protected = 0);
        $html4 = $htmlparser->processContent($value, $dir, $conf);

        $content = $htmlparser->substituteMarker($content, $marker, $markContent);
        $content .= $htmlparser->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= $htmlparser->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content = $htmlparser->XHTML_clean($content);
    }


}

?>
