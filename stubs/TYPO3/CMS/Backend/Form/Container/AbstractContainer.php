<?php

namespace TYPO3\CMS\Backend\Form\Container;

use TYPO3\CMS\Backend\Form\AbstractNode;

if (class_exists('TYPO3\CMS\Backend\Form\Container\AbstractContainer')) {
    return;
}

abstract class AbstractContainer extends AbstractNode
{
}
