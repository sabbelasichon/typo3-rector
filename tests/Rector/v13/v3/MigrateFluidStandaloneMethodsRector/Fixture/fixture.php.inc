<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateFluidStandaloneMethodsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

final class MediaViewHelper extends AbstractTagBasedViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
    }

    protected function renderImage(FileInterface $image): string
    {
        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');
        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->arguments['title']) && !empty($title)) {
            $this->tag->addAttribute('title', $title);
        }
        return $this->tag->render();
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v13\v3\MigrateFluidStandaloneMethodsRector\Fixture;

use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

final class MediaViewHelper extends AbstractTagBasedViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
    }

    protected function renderImage(FileInterface $image): string
    {
        $alt = $image->getProperty('alternative');
        $title = $image->getProperty('title');
        if (empty($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', $alt);
        }
        if (empty($this->additionalArguments['title']) && !empty($title)) {
            $this->tag->addAttribute('title', $title);
        }
        return $this->tag->render();
    }
}
?>
