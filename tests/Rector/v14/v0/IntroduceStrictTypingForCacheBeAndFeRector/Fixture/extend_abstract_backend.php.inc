<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\AbstractBackend;

class MyCustomBackendImplementation extends AbstractBackend
{
    public function __construct($context, array $options = [])
    {
        parent::__construct($context, $options);
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\IntroduceStrictTypingForCacheBeAndFeRector\Fixture;

use TYPO3\CMS\Core\Cache\Backend\AbstractBackend;

class MyCustomBackendImplementation extends AbstractBackend
{
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }
}

?>
