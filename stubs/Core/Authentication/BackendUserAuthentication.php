<?php
declare(strict_types=1);

namespace TYPO3\CMS\Core\Authentication;

if (class_exists(BackendUserAuthentication::class)) {
    return;
}


final class BackendUserAuthentication
{
    /**
     * @var array
     */
    public $userTS = [
        'tx_news.' => [
            'singleCategoryAcl' => 1
        ]
    ];

    public function getTSConfig($objectString = null, $config = null): array
    {
        return [];
    }
}
