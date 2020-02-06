<?php

declare(strict_types=1);

namespace TYPO3\CMS\Frontend\ContentObject;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Page\PageRepository;

if (class_exists(ContentObjectRenderer::class)) {
    return;
}

final class ContentObjectRenderer
{
    public function RECORDS(array $config): void
    {
        $this->cObjGetSingle('RECORDS', $config);
    }

    public function cObjGetSingle(string $string, array $config): void
    {
    }

    public function enableFields($table, $show_hidden = false, array $ignore_array = [])
    {
        return GeneralUtility::makeInstance(PageRepository::class)->enableFields($table, $show_hidden ? true : -1, $ignore_array);
    }

    public function getSubpart($content, $marker): void
    {
    }

    public function substituteSubpart($content, $marker, $subpartContent, $recursive = 1): void
    {
    }

    public function substituteSubpartArray($content, array $subpartsContent): void
    {
    }

    public function substituteMarker($content, $marker, $markContent): void
    {
    }

    public function substituteMarkerArrayCached($content, array $markContentArray = null, array $subpartContentArray = null, array $wrappedSubpartContentArray = null): void
    {
    }

    public function substituteMarkerArray($content, array $markContentArray, $wrap = '', $uppercase = false, $deleteUnused = false): void
    {
    }

    public function substituteMarkerInObject(&$tree, array $markContentArray)
    {
        GeneralUtility::logDeprecatedFunction();
        if (is_array($tree)) {
            foreach ($tree as $key => $value) {
                $this->templateService->substituteMarkerInObject($tree[$key], $markContentArray);
            }
        } else {
            $tree = $this->templateService->substituteMarkerArray($tree, $markContentArray);
        }

        return $tree;
    }

    public function substituteMarkerAndSubpartArrayRecursive($content, array $markersAndSubparts, $wrap = '', $uppercase = false, $deleteUnused = false): void
    {
    }

    public function fillInMarkerArray(array $markContentArray, array $row, $fieldList = '', $nl2br = true, $prefix = 'FIELD_', $HSC = false)
    {
        return $this->templateService->fillInMarkerArray($markContentArray, $row, $fieldList, $nl2br, $prefix, $HSC, !empty($GLOBALS['TSFE']->xhtmlDoctype));
    }
}
