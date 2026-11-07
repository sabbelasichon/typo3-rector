<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

class MyFlexFormPostHook
{
    public function parseDataStructureByIdentifierPostProcess(array $identifier, array $dataStructure): array
    {
        $dataStructure['sheets']['sDEF']['ROOT']['TCEforms']['sheetTitle'] = 'Modified';
        return $dataStructure;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\MigrateFlexFormParsingHooksToEventListenersRector\Fixture;

use TYPO3\CMS\Core\Configuration\Event\AfterFlexFormDataStructureParsedEvent;

class MyFlexFormPostHook
{
    /**
     * @todo Register this listener in Configuration/Services.yaml. See https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/12.0/Feature-97449-PSR-14EventsForModifyingFlexFormParsing.html
     */
    public function handleAfterFlexFormDataStructureParsed(AfterFlexFormDataStructureParsedEvent $event): void
    {
        $dataStructure['sheets']['sDEF']['ROOT']['TCEforms']['sheetTitle'] = 'Modified';
        return $dataStructure;
    }
}

?>
