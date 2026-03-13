<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\MigrateFluidClassAliasesRector\Fixture;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception;

class MyViewHelper extends AbstractTagBasedViewHelper implements CompilableInterface
{
    public function render(): string
    {
        try {
            return 'output';
        } catch (Exception $e) {
            return '';
        }
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v10\v0\MigrateFluidClassAliasesRector\Fixture;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;

class MyViewHelper extends AbstractTagBasedViewHelper implements ViewHelperInterface
{
    public function render(): string
    {
        try {
            return 'output';
        } catch (Exception $e) {
            return '';
        }
    }
}

?>
