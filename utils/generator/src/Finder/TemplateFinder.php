<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Generator\Finder;

use Symplify\SmartFileSystem\SmartFileInfo;

final class TemplateFinder
{
    /**
     * @var string
     */
    public const TEMPLATES_DIRECTORY = __DIR__ . '/../../templates';

    /**
     * @return SmartFileInfo[]
     */
    public function find(string $type): array
    {
        $filePaths = $this->addRuleAndTestCase($type);

        $smartFileInfos = [];
        foreach ($filePaths as $filePath) {
            $smartFileInfos[] = new SmartFileInfo($filePath);
        }

        return $smartFileInfos;
    }

    /**
     * @return array<int, string>
     */
    private function addRuleAndTestCase(string $type): array
    {
        $filePaths = [];

        $filePaths[] = __DIR__ . '/../../templates/src/Rector/__Major__/__Minor__/' . $type . '/__Name__.php';

        $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/' . $type . '/__Test_Directory__/__Name__Test.php.inc';

        switch ($type) {
            case 'flexform':
                $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/' . $type . '/__Test_Directory__/Fixture/fixture.xml';
                break;
            case 'typoscript':
                $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/' . $type . '/__Test_Directory__/Fixture/fixture.typoscript';
                break;
            default:
                $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/' . $type . '/__Test_Directory__/Fixture/fixture.php.inc';
                break;
        }

        $filePaths[] = __DIR__ . '/../../templates/tests/Rector/__Major__/__Minor__/' . $type . '/__Test_Directory__/config/configured_rule.php.inc';

        return $filePaths;
    }
}
