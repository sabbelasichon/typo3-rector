<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Source\ExampleRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MyController extends ActionController
{
    protected ExampleRepository $exampleRepository;

    public function injectExampleRepository(ExampleRepository $exampleRepository)
    {
        $this->exampleRepository = $exampleRepository;
    }

    public function myAction()
    {
        $this->exampleRepository->findByFoo('bar');
        $this->exampleRepository->findByFooBar('bar');
        $bar = 'bar';
        $this->exampleRepository->findByFoo($bar);
        $this->exampleRepository->findOneByFoo('bar');
        $this->exampleRepository->countByFoo('bar');

        $this->exampleRepository->findByUid(1);

        $this->exampleRepository->findByMethodExists('bar');
    }
}

class MyRepository extends Repository
{
    public function findByUids(array $uids): array
    {
        $result = [];
        foreach ($uids as $uid) {
            $contact = $this->findByUid($uid);
            if ($contact) {
                $result[] = $contact;
            }
        }
        return $result;
    }
}

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Fixture;

use Ssch\TYPO3Rector\Tests\Rector\v12\v3\typo3\MigrateMagicRepositoryMethodsRector\Source\ExampleRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MyController extends ActionController
{
    protected ExampleRepository $exampleRepository;

    public function injectExampleRepository(ExampleRepository $exampleRepository)
    {
        $this->exampleRepository = $exampleRepository;
    }

    public function myAction()
    {
        $this->exampleRepository->findBy(['foo' => 'bar']);
        $this->exampleRepository->findBy(['fooBar' => 'bar']);
        $bar = 'bar';
        $this->exampleRepository->findBy(['foo' => $bar]);
        $this->exampleRepository->findOneBy(['foo' => 'bar']);
        $this->exampleRepository->count(['foo' => 'bar']);

        $this->exampleRepository->findByUid(1);

        $this->exampleRepository->findByMethodExists('bar');
    }
}

class MyRepository extends Repository
{
    public function findByUids(array $uids): array
    {
        $result = [];
        foreach ($uids as $uid) {
            $contact = $this->findByUid($uid);
            if ($contact) {
                $result[] = $contact;
            }
        }
        return $result;
    }
}

?>
