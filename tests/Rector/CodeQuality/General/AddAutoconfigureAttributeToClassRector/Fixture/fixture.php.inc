<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector\Fixture;

class MyPublicService
{
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\AddAutoconfigureAttributeToClassRector\Fixture;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class MyPublicService
{
}

?>
