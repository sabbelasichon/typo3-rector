<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\PrependAbsolutePathToGetFileAbsFileNameRector\Fixture;

use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

class SomeFooBar
{
    /**
     * @var GraphicalFunctions
     */
    private $graphicalFunctions;

    /**
     * SomeFooBar constructor.
     *
     * @param GraphicalFunctions $graphicalFunctions
     */
    public function __construct(GraphicalFunctions $graphicalFunctions)
    {
        $this->graphicalFunctions = $graphicalFunctions;
        $this->graphicalFunctions->prependAbsolutePath('some.font');
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Tests\Rector\v8\v0\PrependAbsolutePathToGetFileAbsFileNameRector\Fixture;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\GraphicalFunctions;

class SomeFooBar
{
    /**
     * @var GraphicalFunctions
     */
    private $graphicalFunctions;

    /**
     * SomeFooBar constructor.
     *
     * @param GraphicalFunctions $graphicalFunctions
     */
    public function __construct(GraphicalFunctions $graphicalFunctions)
    {
        $this->graphicalFunctions = $graphicalFunctions;
        GeneralUtility::getFileAbsFileName('some.font');
    }
}

?>
