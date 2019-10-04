<?php

final class SomeIgnoreValidationClass
{
    /**
     * @var string
     */
    private $someProperty;

    /**
     * @ignorevalidation $param
     */
    public function someMethod($param): void
    {
    }
}

?>
-----
<?php

final class SomeIgnoreValidationClass
{
    /**
     * @var string
     */
    private $someProperty;

    /**
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("param")
     */
    public function someMethod($param): void
    {
    }
}

?>
