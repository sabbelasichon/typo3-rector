<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Resource;

if (interface_exists('TYPO3\CMS\Core\Resource\FolderInterface')) {
    return;
}

interface FolderInterface extends ResourceInterface
{

}
