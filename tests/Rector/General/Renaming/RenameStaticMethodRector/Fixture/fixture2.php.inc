<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Source\FormMacros;

function staticMethodNameReplacer2()
{
    $result = FormMacros::renderFormBegin($someArgs);
    $result = FormMACROS::renderFormBEGIN($someArgs);
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Source\FormMacros;

function staticMethodNameReplacer2()
{
    $result = \Nette\Bridges\FormsLatte\Runtime::renderFormBegin($someArgs);
    $result = \Nette\Bridges\FormsLatte\Runtime::renderFormBegin($someArgs);
}

?>
