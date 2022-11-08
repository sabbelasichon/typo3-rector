<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v9\v5\ExtbaseCommandControllerToSymfonyCommandRector\Extension\Classes\Controller\Command;

use stdClass;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\CacheService;

final class TestCommand extends CommandController
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var CacheService
     */
    private $cacheService;

    public function __construct(ObjectManager $objectManager, CacheService $cacheService)
    {
        $this->objectManager = $objectManager;
        $this->cacheService = $cacheService;
    }

    /**
     * This is the description of the command
     *
     * @param string $seasonKey The season key
     * @param string $clearCachePageUids Clear cache page uids
     *
     * @throws Exception
     */
    public function importTeamCommand(string $seasonKey, string $clearCachePageUids = 'Test'): void
    {
        $teamObj = $this->objectManager->get(stdClass::class);

        if ('' !== $clearCachePageUids) {
            $this->cacheService->clearPageCache(explode(',', $clearCachePageUids));
        }
        $this->outputLine('foobar');
        GeneralUtility::makeInstance(CacheManager::class)->flushCachesByTag('dataimport');
    }

    /**
     * This is the description of the command
     *
     * @param string $seasonKey The season key
     * @param string $clearCachePageUids Clear cache page uids
     *
     * @throws Exception
     */
    public function importAnotherCommand(string $seasonKey, string $clearCachePageUids = 'Test'): void
    {
        $teamObj = $this->objectManager->get(stdClass::class);

        if ('' !== $clearCachePageUids) {
            $this->cacheService->clearPageCache(explode(',', $clearCachePageUids));
        }

        GeneralUtility::makeInstance(CacheManager::class)->flushCachesByTag('dataimport');
    }
}
