<?php

final class SomeTransientClass
{
    /**
     * @transient
     */
    protected $transient;
}

?>
-----
<?php

final class SomeTransientClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\ORM\Transient
     */
    protected $transient;
}

?>
