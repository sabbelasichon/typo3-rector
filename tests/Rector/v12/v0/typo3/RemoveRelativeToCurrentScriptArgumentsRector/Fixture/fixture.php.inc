<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Source\TestFileRendering;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\VideoTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer;
use TYPO3\CMS\Core\Resource\Rendering\YoutubeRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var File $file */
$file = GeneralUtility::makeInstance(File::class);
$width = '123';
$height = '22';
$options = [];
$relative = true;

/** @var TestFileRendering $testFileRendering */
$testFileRendering = GeneralUtility::makeInstance(TestFileRendering::class);
$foo = $testFileRendering->render($file, $width, $height, $options, $relative);
$fooNoArg = $testFileRendering->render($file, $width, $height);

/** @var AudioTagRenderer $audioTagRenderer */
$audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
$foo = $audioTagRenderer->render($file, $width, $height, $options, $relative);
$videoTagRenderer = GeneralUtility::makeInstance(VideoTagRenderer::class);
$foo = $videoTagRenderer->render($file, $width, $height, $options, $relative);
$vimeoRenderer = GeneralUtility::makeInstance(VimeoRenderer::class);
$foo = $vimeoRenderer->render($file, $width, $height, $options, $relative);
$youtubeRenderer = GeneralUtility::makeInstance(YoutubeRenderer::class);
$foo = $youtubeRenderer->render($file, $width, $height, $options, $relative);


$image = GeneralUtility::makeInstance(Image::class);
$url = $image->getUrl(true);
$urlTwo = $image->getUrl();

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\RemoveRelativeToCurrentScriptArgumentsRector\Source\TestFileRendering;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Rendering\AudioTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\VideoTagRenderer;
use TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer;
use TYPO3\CMS\Core\Resource\Rendering\YoutubeRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/** @var File $file */
$file = GeneralUtility::makeInstance(File::class);
$width = '123';
$height = '22';
$options = [];
$relative = true;

/** @var TestFileRendering $testFileRendering */
$testFileRendering = GeneralUtility::makeInstance(TestFileRendering::class);
$foo = $testFileRendering->render($file, $width, $height, $options);
$fooNoArg = $testFileRendering->render($file, $width, $height);

/** @var AudioTagRenderer $audioTagRenderer */
$audioTagRenderer = GeneralUtility::makeInstance(AudioTagRenderer::class);
$foo = $audioTagRenderer->render($file, $width, $height, $options);
$videoTagRenderer = GeneralUtility::makeInstance(VideoTagRenderer::class);
$foo = $videoTagRenderer->render($file, $width, $height, $options);
$vimeoRenderer = GeneralUtility::makeInstance(VimeoRenderer::class);
$foo = $vimeoRenderer->render($file, $width, $height, $options);
$youtubeRenderer = GeneralUtility::makeInstance(YoutubeRenderer::class);
$foo = $youtubeRenderer->render($file, $width, $height, $options);


$image = GeneralUtility::makeInstance(Image::class);
$url = $image->getUrl();
$urlTwo = $image->getUrl();

?>
