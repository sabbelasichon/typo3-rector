<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

class MyIdentifierPreHook
{
    public function getDataStructureIdentifierPreProcess(array $fieldTca, string $tableName, string $fieldName, array $row): array
    {
        if ($tableName === 'tx_myext_sometable') {
            return ['type' => 'my_custom_type'];
        }
        return [];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureIdentifierInitializedEvent;

class MyIdentifierPreHook
{
    /**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
    public function handleBeforeFlexFormDataStructureIdentifierInitialized(BeforeFlexFormDataStructureIdentifierInitializedEvent $event): void
    {
        if ($tableName === 'tx_myext_sometable') {
            return ['type' => 'my_custom_type'];
        }
        return [];
    }
}

?>
