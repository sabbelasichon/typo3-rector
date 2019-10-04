<?php

final class SomeTransientClass
{
    /**
     * @transient
     */
    private $transient;
}

?>
-----
<?php

final class SomeTransientClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    private $transient;
}

?>
