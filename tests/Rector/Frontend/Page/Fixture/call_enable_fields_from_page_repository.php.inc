<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ContentObjectRendererEnableFieldsMethodCall
{
    public function method(): void
    {
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $enableFields1 = $contentObjectRenderer->enableFields('pages');
        $enableFields2 = $contentObjectRenderer->enableFields('pages', true);
        $enableFields3 = $contentObjectRenderer->enableFields('pages', false, []);
    }
}

?>
-----
<?php

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

final class ContentObjectRendererEnableFieldsMethodCall
{
    public function method(): void
    {
        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $enableFields1 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Domain\Repository\PageRepository::class)->enableFields('pages');
        $enableFields2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Domain\Repository\PageRepository::class)->enableFields('pages', true);
        $enableFields3 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Domain\Repository\PageRepository::class)->enableFields('pages', -1, []);
    }
}

?>
