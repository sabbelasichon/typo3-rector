<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

class MyFlexFormHook
{
    public function parseDataStructureByIdentifierPreProcess(array $identifier): array
    {
        if ($identifier['type'] === 'my_type') {
            return ['ROOT' => []];
        }
        return [];
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

use TYPO3\CMS\Core\Configuration\Event\BeforeFlexFormDataStructureParsedEvent;

class MyFlexFormHook
{
    /**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
    public function handleBeforeFlexFormDataStructureParsed(BeforeFlexFormDataStructureParsedEvent $event): void
    {
        if ($identifier['type'] === 'my_type') {
            return ['ROOT' => []];
        }
        return [];
    }
}

?>
