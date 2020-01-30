<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Migrations\Tca;

final class CompositeTcaMigration implements TcaMigration
{
    /**
     * @var array|TcaMigration[]
     */
    private $tcaMigrations;

    public function __construct(array $tcaMigrations)
    {
        $this->tcaMigrations = $tcaMigrations;
    }

    public function migrate(array $tca)
    {
        foreach ($this->tcaMigrations as $tcaMigration) {
            $tca = $tcaMigration->migrate($tca);
        }

        return $tca;
    }
}
