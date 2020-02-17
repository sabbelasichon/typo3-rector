<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82438-DeprecationMethods.html
 */
final class RefactorDeprecationLogRector extends AbstractRector
{
    /**
     * List of nodes this class checks, classes that implements \PhpParser\Node
     * See beautiful map of all nodes https://github.com/rectorphp/rector/blob/master/docs/NodesOverview.md.
     *
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * Process Node of matched type.
     *
     * @param Node|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $classNode = $node->class;
        $className = $this->getName($classNode);
        $methodName = $this->getName($node);
        $arguments = $node->args;

        if (GeneralUtility::class !== $className) {
            return null;
        }

        $const = new ConstFetch(new Name(['name' => 'E_USER_DEPRECATED']));
        $usefulMessage = new String_('A useful message');
        $emptyFallbackString = new String_('');

        switch ($methodName) {
            case 'logDeprecatedFunction':
            case 'logDeprecatedViewHelperAttribute':
                return $this->createFunction('trigger_error', [$usefulMessage, $const]);
                break;
            case 'deprecationLog':
                return $this->createFunction(
                    'trigger_error',
                    [$arguments[0] ?? $emptyFallbackString, $const]
                );
                break;
            case 'getDeprecationLogFileName':
                $this->removeNode($node);

                return null;
                break;
            default:
                return null;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Refactor GeneralUtility deprecationLog methods', [
            new CodeSample(
                <<<'PHP'
GeneralUtility::logDeprecatedFunction();
GeneralUtility::logDeprecatedViewHelperAttribute();
GeneralUtility::deprecationLog('Message');
GeneralUtility::getDeprecationLogFileName();
PHP
                ,
                <<<'PHP'
trigger_error('A useful message', E_USER_DEPRECATED);
PHP
            ),
        ]);
    }
}
