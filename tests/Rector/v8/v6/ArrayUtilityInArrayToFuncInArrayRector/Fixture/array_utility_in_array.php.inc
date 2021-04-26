<?php

use TYPO3\CMS\Core\Utility\ArrayUtility;

ArrayUtility::inArray(['one', 'two', 'three'], 'one');

?>
-----
<?php

use TYPO3\CMS\Core\Utility\ArrayUtility;

in_array('one', ['one', 'two', 'three'], true);

?>
