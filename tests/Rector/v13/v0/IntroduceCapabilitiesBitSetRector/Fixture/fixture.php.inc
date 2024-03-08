<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\IntroduceCapabilitiesBitSetRector\Fixture;

use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

echo ResourceStorageInterface::CAPABILITY_BROWSABLE;
echo ResourceStorageInterface::CAPABILITY_PUBLIC;
echo ResourceStorageInterface::CAPABILITY_WRITABLE;
echo ResourceStorageInterface::CAPABILITY_HIERARCHICAL_IDENTIFIERS;

function doSomething($cap) {
    echo $cap;
}

doSomething(\TYPO3\CMS\Core\Resource\ResourceStorageInterface::CAPABILITY_BROWSABLE);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\IntroduceCapabilitiesBitSetRector\Fixture;

use TYPO3\CMS\Core\Resource\Capabilities;
use TYPO3\CMS\Core\Resource\ResourceStorageInterface;

echo Capabilities::CAPABILITY_BROWSABLE;
echo Capabilities::CAPABILITY_PUBLIC;
echo Capabilities::CAPABILITY_WRITABLE;
echo Capabilities::CAPABILITY_HIERARCHICAL_IDENTIFIERS;

function doSomething($cap) {
    echo $cap;
}

doSomething(Capabilities::CAPABILITY_BROWSABLE);

?>
