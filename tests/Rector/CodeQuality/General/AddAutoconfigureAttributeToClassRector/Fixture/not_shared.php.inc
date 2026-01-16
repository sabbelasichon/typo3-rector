<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector\Fixture;

class NotSharedService
{
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector\Fixture;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true, shared: false)]
class NotSharedService
{
}

?>
