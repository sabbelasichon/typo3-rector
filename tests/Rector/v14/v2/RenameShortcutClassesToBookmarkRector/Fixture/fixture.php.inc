<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RenameShortcutClassesToBookmarkRector\Fixture;

use TYPO3\CMS\Backend\Backend\ToolbarItems\ShortcutToolbarItem;
use TYPO3\CMS\Backend\Controller\ShortcutController;

final class MyToolbarItem extends ShortcutToolbarItem
{
}

$controllerClass = ShortcutController::class;
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v2\RenameShortcutClassesToBookmarkRector\Fixture;

use TYPO3\CMS\Backend\Backend\ToolbarItems\BookmarkToolbarItem;
use TYPO3\CMS\Backend\Controller\BookmarkController;

final class MyToolbarItem extends BookmarkToolbarItem
{
}

$controllerClass = BookmarkController::class;
?>
