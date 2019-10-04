<?php

final class SomeValidateClass
{
    /**
     * @validate NotEmpty
     * @validate StringLength(minimum=0, maximum=255)
     */
    private $validate;
}

?>
-----
<?php

final class SomeValidateClass
{
    /**
     * @TYPO3\CMS\Extbase\Annotation\Validate(validator="NotEmpty")
     * @TYPO3\CMS\Extbase\Annotation\Validate("StringLength", options={"minimum": 0,"maximum": 255})
     */
    private $validate;
}

?>
