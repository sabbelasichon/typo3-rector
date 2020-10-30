<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectMarkerRendererMethods
{
    protected $cObj;

    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $this->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer::class);
        $template = $this->cObj->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = $this->cObj->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = $this->cObj->substituteSubpartArray($html2, []);
        $content = $this->cObj->substituteMarker($content, $marker, $markContent);
        $content .= $this->cObj->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
        $content .= $this->cObj->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= $this->cObj->substituteMarkerInObject($tree, $markContentArray);
        $content .= $this->cObj->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content .= $this->cObj->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC);
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\Frontend\ContentObject\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Core\Service\MarkerBasedTemplateService;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

final class ContentObjectMarkerRendererMethods
{
    protected $cObj;

    public function doSomething(): void
    {
        $template = '';
        $markerArray = [];
        $subpartArray = [];
        $this->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $template = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->getSubpart($this->config['templateFile'], '###TEMPLATE###');
        $html = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpart($html, '###ADDITONAL_KEYWORD###', '');
        $html2 = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteSubpartArray($html2, []);
        $content = GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarker($content, $marker, $markContent);
        $content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArrayCached($template, $markerArray, $subpartArray, []);
        $content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerArray($content, $markContentArray, $wrap, $uppercase, $deleteUnused);
        $content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerInObject($tree, $markContentArray);
        $content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->substituteMarkerAndSubpartArrayRecursive($content, $markersAndSubparts, $wrap, $uppercase, $deleteUnused);
        $content .= GeneralUtility::makeInstance(MarkerBasedTemplateService::class)->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC, !empty($GLOBALS['TSFE']->xhtmlDoctype));
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}

?>
