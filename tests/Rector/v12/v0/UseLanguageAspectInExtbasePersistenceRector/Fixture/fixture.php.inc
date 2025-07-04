<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseLanguageAspectInExtbasePersistenceRector\Fixture;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MyRepository extends Repository
{
    public function findSomething(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageOverlayMode(false);

        return $query->matching($query->equals('something', 2))->execute();
    }

    public function findSomething2(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageOverlayMode(true);

        return $query->matching($query->equals('something', 2))->execute();
    }

    public function findSomething3(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setLanguageOverlayMode('hideNonTranslated');

        return $query->matching($query->equals('something', 2))->execute();
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\UseLanguageAspectInExtbasePersistenceRector\Fixture;

use TYPO3\CMS\Core\Context\LanguageAspect;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MyRepository extends Repository
{
    public function findSomething(): QueryResultInterface
    {
        $query = $this->createQuery();
        $languageAspect = $query->getQuerySettings()->getLanguageAspect();
        $languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_OFF);
        $query->getQuerySettings()->setLanguageAspect($languageAspect);

        return $query->matching($query->equals('something', 2))->execute();
    }

    public function findSomething2(): QueryResultInterface
    {
        $query = $this->createQuery();
        $languageAspect = $query->getQuerySettings()->getLanguageAspect();
        $languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_MIXED);
        $query->getQuerySettings()->setLanguageAspect($languageAspect);

        return $query->matching($query->equals('something', 2))->execute();
    }

    public function findSomething3(): QueryResultInterface
    {
        $query = $this->createQuery();
        $languageAspect = $query->getQuerySettings()->getLanguageAspect();
        $languageAspect = new LanguageAspect($languageAspect->getId(), $languageAspect->getContentId(), LanguageAspect::OVERLAYS_ON);
        $query->getQuerySettings()->setLanguageAspect($languageAspect);

        return $query->matching($query->equals('something', 2))->execute();
    }
}

?>
