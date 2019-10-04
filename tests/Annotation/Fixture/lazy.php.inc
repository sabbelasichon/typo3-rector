<?php

final class SomeLazyClass
{
    /**
     * @lazy
     */
    private $someProperty;
}

?>
-----
<?php

final class SomeLazyClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    private $someProperty;
}

?>
