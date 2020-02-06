<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class RefactorExplodeUrl2ArrayFromGeneralUtilityRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @inheritDoc
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node->expr, GeneralUtility::class)) {
            return null;
        }

        if (!$this->isName($node->expr->name, 'explodeUrl2Array')) {
            return null;
        }

        $arguments = $node->expr->args;

        if (count($arguments) <= 1) {
            return null;
        }

        $lastArgument = array_pop($arguments);

        if ($this->isFalse($lastArgument->value)) {
            $node->expr->args = $arguments;

            return null;
        }

        return $this->createFunction('parse_str', [$arguments[0], $node->var]);
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Remove second argument of GeneralUtility::explodeUrl2Array if it is false or just use function parse_str if it is true', [
            new CodeSample(
                <<<'PHP'
$variable = GeneralUtility::explodeUrl2Array('https://www.domain.com', true);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com', false);
PHP
                ,
                <<<'PHP'
parse_str('https://www.domain.com', $variable);
$variable2 = GeneralUtility::explodeUrl2Array('https://www.domain.com');
PHP
            ),
        ]);
    }
}
