<?php

namespace TYPO3\CMS\Core\Resource;

if(interface_exists('TYPO3\CMS\Core\Resource\Capabilities')) {
    return;
}

interface Capabilities
{

    public const CAPABILITY_BROWSABLE = 'CAPABILITY_BROWSABLE';
    public const CAPABILITY_PUBLIC = 'CAPABILITY_PUBLIC';
    public const CAPABILITY_WRITABLE = 'CAPABILITY_WRITABLE';
    public const CAPABILITY_HIERARCHICAL_IDENTIFIERS = 'CAPABILITY_HIERARCHICAL_IDENTIFIERS';

}
