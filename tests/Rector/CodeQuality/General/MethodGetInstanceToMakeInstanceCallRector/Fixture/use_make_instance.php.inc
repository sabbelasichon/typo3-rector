<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\Fixture;

$instance = \TYPO3\CMS\Core\Resource\Index\ExtractorRegistry::getInstance();
$instance = \TYPO3\CMS\Core\Resource\Index\FileIndexRepository::getInstance();
$instance = \TYPO3\CMS\Core\Resource\Index\MetaDataRepository::getInstance();
$instance = \TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry::getInstance();
$instance = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
$instance = \TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry::getInstance();
$instance = \TYPO3\CMS\Form\Service\TranslationService::getInstance();
$instance = \TYPO3\CMS\T3editor\Registry\AddonRegistry::getInstance();
$instance = \TYPO3\CMS\T3editor\Registry\ModeRegistry::getInstance();
$instance = \Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\Source\MySourceClass::getInstance();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\Index\ExtractorRegistry;
use TYPO3\CMS\Core\Resource\Index\FileIndexRepository;
use TYPO3\CMS\Core\Resource\Index\MetaDataRepository;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Resource\TextExtraction\TextExtractorRegistry;
use TYPO3\CMS\Form\Service\TranslationService;
use TYPO3\CMS\T3editor\Registry\AddonRegistry;
use TYPO3\CMS\T3editor\Registry\ModeRegistry;
use Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\MethodGetInstanceToMakeInstanceCallRector\Source\MySourceClass;

$instance = GeneralUtility::makeInstance(ExtractorRegistry::class);
$instance = GeneralUtility::makeInstance(FileIndexRepository::class);
$instance = GeneralUtility::makeInstance(MetaDataRepository::class);
$instance = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class);
$instance = GeneralUtility::makeInstance(RendererRegistry::class);
$instance = GeneralUtility::makeInstance(TextExtractorRegistry::class);
$instance = GeneralUtility::makeInstance(TranslationService::class);
$instance = GeneralUtility::makeInstance(AddonRegistry::class);
$instance = GeneralUtility::makeInstance(ModeRegistry::class);
$instance = MySourceClass::getInstance();

?>
