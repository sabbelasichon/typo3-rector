<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.1/Breaking-75454-TYPO3_dbConstantsRemoved.html
 */
final class RefactorDbConstantsRector extends AbstractRector
{
    private static $mapConstantsToGlobals = [
        'TYPO3_db' => 'dbname',
        'TYPO3_db_username' => 'user',
        'TYPO3_db_password' => 'password',
        'TYPO3_db_host' => 'host',
    ];

    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        $constantsName = $this->getName($node);

        if (!array_key_exists($constantsName, self::$mapConstantsToGlobals)) {
            return null;
        }

        $globalKey = self::$mapConstantsToGlobals[$constantsName];

        return new ArrayDimFetch(
            new ArrayDimFetch(
                new ArrayDimFetch(
                    new ArrayDimFetch(
                        new ArrayDimFetch(
                            new Variable('GLOBALS'),
                            new String_('TYPO3_CONF_VARS')
                        ),
                        new String_('DB')
                    ), new String_('Connections')
                ), new String_('Default')
            ), new String_($globalKey)
        );
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Changes TYPO3_db constants to $GLOBALS[\'TYPO3_CONF_VARS\'][\'DB\'][\'Connections\'][\'Default\'].',
            [
                new CodeSample(
                    <<<'PHP'
$database = TYPO3_db;
$username = TYPO3_db_username;
$password = TYPO3_db_password;
$host = TYPO3_db_host;
PHP
                    ,
                    <<<'PHP'
$database = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'];
$username = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'];
$password = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'];
$host = $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'];
PHP
                ),
            ]
        );
    }
}
