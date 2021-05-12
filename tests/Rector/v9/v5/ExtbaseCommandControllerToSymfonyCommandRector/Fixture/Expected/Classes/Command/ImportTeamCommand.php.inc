<?php

namespace Ssch\TYPO3Rector\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Service\CacheService;

class ImportTeamCommand extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var CacheService
     */
    private $cacheService;

    public function __construct(string $name = null, ObjectManagerInterface $objectManager, CacheService $cacheService)
    {
        parent::__construct($name);
        $this->objectManager = $objectManager;
        $this->cacheService = $cacheService;
    }

    protected function configure(): void
    {
        $this->setDescription('This is the description of the command');
        $this->addArgument('seasonKey', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'The season key', null);
        $this->addArgument('clearCachePageUids', \Symfony\Component\Console\Input\InputArgument::OPTIONAL, 'Clear cache page uids', 'Test');
    }
    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output): int
    {
        $seasonKey = $input->getArgument('seasonKey');
        $clearCachePageUids = $input->getArgument('clearCachePageUids');
        $teamObj = $this->objectManager->get(\stdClass::class);
        if ('' !== $clearCachePageUids) {
            $this->cacheService->clearPageCache(explode(',', $clearCachePageUids));
        }
        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)->flushCachesByTag('dataimport');
        return 0;
    }
}
