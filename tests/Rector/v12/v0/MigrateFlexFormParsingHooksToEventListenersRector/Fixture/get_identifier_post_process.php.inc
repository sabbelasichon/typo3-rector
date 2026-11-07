<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

class MyIdentifierPostHook
{
    public function getDataStructureIdentifierPostProcess(array $fieldTca, string $tableName, string $fieldName, array $row, array $identifier): array
    {
        $identifier['type'] = 'modified_type';
        return $identifier;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureIdentifierInitializedEvent;

class MyIdentifierPostHook
{
    /**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
    public function handleAfterFlexFormDataStructureIdentifierInitialized(AfterFlexFormDataStructureIdentifierInitializedEvent $event): void
    {
        $identifier['type'] = 'modified_type';
        return $identifier;
    }
}

?>
