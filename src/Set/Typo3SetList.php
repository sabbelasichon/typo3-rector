<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Set;

final class Typo3SetList
{
    /**
     * @var string
     */
    public const TYPO3_104 = __DIR__ . '/../../config/typo3-10.php';

    /**
     * @var string
     */
    public const TCA_104 = __DIR__ . '/../../config/v10/tca-104.php';

    /**
     * @var string
     */
    public const TYPO3_11 = __DIR__ . '/../../config/typo3-11.php';

    /**
     * @var string
     * @deprecated Use TCA_114 instead.
     */
    public const TCA_110 = self::TCA_114;

    /**
     * @var string
     * @deprecated Use TCA_114 instead.
     */
    public const TCA_113 = self::TCA_114;

    /**
     * @var string
     */
    public const TCA_114 = __DIR__ . '/../../config/v11/tca-114.php';

    /**
     * @var string
     */
    public const TYPO3_12 = __DIR__ . '/../../config/typo3-12.php';

    /**
     * @var string
     */
    public const TCA_120 = __DIR__ . '/../../config/v12/tca-120.php';

    /**
     * @var string
     */
    public const TCA_123 = __DIR__ . '/../../config/v12/tca-123.php';
}
