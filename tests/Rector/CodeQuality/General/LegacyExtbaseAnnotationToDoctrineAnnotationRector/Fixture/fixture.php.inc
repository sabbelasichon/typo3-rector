<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\LegacyExtbaseAnnotationToDoctrineAnnotationRector\Fixture;

class MyClass
{
    /**
     * @var \Some\Service
     * @inject
     */
    private $someService;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @lazy
     */
    protected $items;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     * @cascade remove
     */
    protected $images;

    /**
     * @validate NotEmpty
     */
    protected $title;

    /**
     * @validate EmailAddress
     */
    protected $email;

    /**
     * @validate DateTime
     */
    protected $date;

    /**
     * @validate Boolean
     */
    protected $active;

    /**
     * @ignorevalidation $param
     */
    public function myAction($param) {}
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\CodeQuality\General\LegacyExtbaseAnnotationToDoctrineAnnotationRector\Fixture;

use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;

class MyClass
{
    /**
     * @var \Some\Service
     * @Inject
     */
    private $someService;

    /**
     * @var ObjectStorage
     * @Lazy
     */
    protected $items;

    /**
     * @var ObjectStorage
     * @Cascade("remove")
     */
    protected $images;

    /**
     * @Validate("NotEmpty")
     */
    protected $title;

    /**
     * @Validate("EmailAddress")
     */
    protected $email;

    /**
     * @Validate("DateTime")
     */
    protected $date;

    /**
     * @Validate("Boolean")
     */
    protected $active;

    /**
     * @IgnoreValidation("param")
     */
    public function myAction($param) {}
}

?>
