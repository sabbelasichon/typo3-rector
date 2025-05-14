<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector\Fixture;

$doctrineType = \TYPO3\CMS\Core\Database\Schema\Types\EnumType::TYPE;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v4\ReplaceTYPO3EnumTypeWithDoctrineDBALEnumTypeRector\Fixture;

use Doctrine\DBAL\Types\Types;

$doctrineType = Types::ENUM;
?>
