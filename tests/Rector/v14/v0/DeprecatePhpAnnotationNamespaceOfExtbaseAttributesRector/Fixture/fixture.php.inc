<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DeprecatePhpAnnotationNamespaceOfExtbaseAttributesRector\Fixture;

use TYPO3\CMS\Extbase\Annotation\FileUpload;
use TYPO3\CMS\Extbase\Annotation\IgnoreValidation;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation\ORM\Transient;
use TYPO3\CMS\Extbase\Annotation\Validate;

class MyClass
{
    #[FileUpload]
    protected string $file;

    #[Cascade('remove')]
    protected string $cascadeProperty;

    #[Lazy]
    protected string $lazyProperty;

    #[Transient]
    protected string $transientProperty;

    #[IgnoreValidation('param')]
    #[Validate(['validator' => 'NotEmpty'])]
    public function myAction(string $param): void
    {
    }
}
?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\DeprecatePhpAnnotationNamespaceOfExtbaseAttributesRector\Fixture;

use TYPO3\CMS\Extbase\Attribute\FileUpload;
use TYPO3\CMS\Extbase\Attribute\ORM\Cascade;
use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Attribute\ORM\Transient;
use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\Validate;

class MyClass
{
    #[FileUpload]
    protected string $file;

    #[Cascade('remove')]
    protected string $cascadeProperty;

    #[Lazy]
    protected string $lazyProperty;

    #[Transient]
    protected string $transientProperty;

    #[IgnoreValidation('param')]
    #[Validate(['validator' => 'NotEmpty'])]
    public function myAction(string $param): void
    {
    }
}
?>
