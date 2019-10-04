<?php

final class SomeOtherClass
{
    /**
     * @cascade
     */
    private $someProperty;
}

?>
-----
<?php

final class SomeOtherClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade
     */
    private $someProperty;
}

?>
