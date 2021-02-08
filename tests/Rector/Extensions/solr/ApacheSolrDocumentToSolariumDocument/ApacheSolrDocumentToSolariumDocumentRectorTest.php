<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\Extensions\solr\ApacheSolrDocumentToSolariumDocument;

use Iterator;
use Rector\Core\Configuration\Option;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ApacheSolrDocumentToSolariumDocumentRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fileInfo): void
    {
        $this->setParameter(Option::AUTO_IMPORT_NAMES, true);
        $this->doTestFileInfo($fileInfo);
    }

    public function provideData(): Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    protected function provideConfigFileInfo(): ?SmartFileInfo
    {
        return new SmartFileInfo(Typo3SetList::SOLR_SOLR_PHP_CLIENT_TO_SOLARIUM);
    }
}
