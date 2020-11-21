<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v3;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.3/Deprecation-85012-OnlyValidateMethodParamsIfNeeded.html
 */
class RenameMethodGetValidationResultsRector extends AbstractRector
{
    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $className = $this->getName($node->class);
        $methodName = $this->getName($node->name);
        if (Argument::class === $className && 'getValidationResults' === $methodName) {
            return $this->createStaticCall(Argument::class, 'validate');
        }
        if (Arguments::class === $className && 'getValidationResults' === $methodName) {
            return $this->createStaticCall(Arguments::class, 'validate');
        }
        return null;
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Migrate the method Argument::getValidationResults() to Argument::validate() and Arguments::getValidationResults() to Arguments::validate()',
            [
                new CodeSample(<<<'PHP'
Argument::getValidationResults();
Arguments::getValidationResults();
PHP
                    , <<<'PHP'
Argument::validate();
Arguments::validate();
PHP
                ),
            ]
        );
    }
}
