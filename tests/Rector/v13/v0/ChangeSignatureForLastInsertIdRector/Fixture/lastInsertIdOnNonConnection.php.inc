<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v0\ChangeSignatureForLastInsertIdRector\Fixture;

$connection = new class
{
    public function lastInsertId(string $table)
    {}
};
$uid = $connection->lastInsertId('table');
?>
