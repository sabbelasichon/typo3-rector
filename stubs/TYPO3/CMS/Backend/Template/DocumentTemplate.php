<?php
namespace TYPO3\CMS\Backend\Template;

use TYPO3\CMS\Core\Page\PageRenderer;

if (class_exists('TYPO3\CMS\Backend\Template\DocumentTemplate')) {
    return;
}

class DocumentTemplate
{
    /**
     * @return void
     * @param string $content
     */
    public function xUaCompatible($content = 'IE=8')
    {

    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    public function getPageRenderer()
    {
        return new PageRenderer();
    }

    /**
     * @return void
     */
    public function addStyleSheet($key, $href, $title = '', $relation = 'stylesheet')
    {
    }

    /**
     * @return void
     */
    public function wrapClickMenuOnIcon($content, $table, $uid = 0, $listFr = true, $addParams = '', $enDisItems = '', $returnTagParameters = false)
    {

    }
}
