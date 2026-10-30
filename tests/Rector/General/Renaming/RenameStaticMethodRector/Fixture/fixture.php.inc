<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Fixture;

function staticMethodNameReplacer()
{
    \Nette\Utils\Html::add('someContent');
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\General\Renaming\RenameStaticMethodRector\Fixture;

function staticMethodNameReplacer()
{
    \Nette\Utils\Html::addHtml('someContent');
}

?>
