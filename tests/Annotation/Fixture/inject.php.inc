<?php

final class SomeInjectClass
{
    /**
     * @var AnnotationReader
     * @inject
     */
    private $inject;
}

?>
-----
<?php

final class SomeInjectClass
{
    /**
     * @var AnnotationReader
     * @TYPO3\CMS\Extbase\Annotation\Inject
     */
    private $inject;
}

?>
