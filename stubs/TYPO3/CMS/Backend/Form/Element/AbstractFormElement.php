<?php

namespace TYPO3\CMS\Backend\Form\Element;

use TYPO3\CMS\Backend\Form\AbstractNode;

if (class_exists('TYPO3\CMS\Backend\Form\Element\AbstractFormElement')) {
    return;
}

abstract class AbstractFormElement extends AbstractNode
{
}
