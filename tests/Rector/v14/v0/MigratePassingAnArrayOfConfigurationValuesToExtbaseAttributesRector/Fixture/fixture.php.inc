<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePassingAnArrayOfConfigurationValuesToExtbaseAttributesRector\Fixture;

use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\ORM\Cascade;
use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Attribute\FileUpload;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class MyClass
{
    #[Validate(['validator' => 'NotEmpty'])]
    protected string $foo = '';

    #[Validate(validator: 'NotEmpty')]
    protected string $fooMigrated = '';

    #[Lazy]
    #[Cascade(['value' => 'remove'])]
    protected ObjectStorage $files;

    #[Cascade('remove')]
    protected ObjectStorage $images;

    #[FileUpload([
        'validation' => [
            'required' => true,
            'maxFiles' => 1,
            'fileSize' => ['minimum' => '0K', 'maximum' => '2M'],
            'allowedMimeTypes' => ['image/jpeg', 'image/png'],
        ],
        'uploadFolder' => '1:/user_upload/files/',
    ])]
    protected ?FileReference $bar = null;

    #[IgnoreValidation(['argumentName' => 'item'])]
    public function editAction(FileReference $item): void
    {
    }

    #[IgnoreValidation(['value' => 'item'])]
    public function editWithValueAction(FileReference $item): void
    {
    }

    #[IgnoreValidation(argumentName: 'item')]
    public function migratedAction(FileReference $item): void
    {
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v14\v0\MigratePassingAnArrayOfConfigurationValuesToExtbaseAttributesRector\Fixture;

use TYPO3\CMS\Extbase\Attribute\IgnoreValidation;
use TYPO3\CMS\Extbase\Attribute\ORM\Cascade;
use TYPO3\CMS\Extbase\Attribute\ORM\Lazy;
use TYPO3\CMS\Extbase\Attribute\FileUpload;
use TYPO3\CMS\Extbase\Attribute\Validate;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class MyClass
{
    #[Validate(validator: 'NotEmpty')]
    protected string $foo = '';

    #[Validate(validator: 'NotEmpty')]
    protected string $fooMigrated = '';

    #[Lazy]
    #[Cascade('remove')]
    protected ObjectStorage $files;

    #[Cascade('remove')]
    protected ObjectStorage $images;

    #[FileUpload(validation: [
        'required' => true,
        'maxFiles' => 1,
        'fileSize' => ['minimum' => '0K', 'maximum' => '2M'],
        'allowedMimeTypes' => ['image/jpeg', 'image/png'],
    ], uploadFolder: '1:/user_upload/files/')]
    protected ?FileReference $bar = null;

    #[IgnoreValidation(argumentName: 'item')]
    public function editAction(FileReference $item): void
    {
    }

    #[IgnoreValidation(argumentName: 'item')]
    public function editWithValueAction(FileReference $item): void
    {
    }

    #[IgnoreValidation(argumentName: 'item')]
    public function migratedAction(FileReference $item): void
    {
    }
}

?>
