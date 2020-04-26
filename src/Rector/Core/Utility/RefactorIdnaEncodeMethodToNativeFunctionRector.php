<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\Core\Utility;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-87894-GeneralUtilityidnaEncode.html
 */
final class RefactorIdnaEncodeMethodToNativeFunctionRector extends AbstractRector
{
    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isMethodStaticCallOrClassMethodObjectType($node, GeneralUtility::class)) {
            return null;
        }

        if (!$this->isName($node->name, 'idnaEncode')) {
            return null;
        }

        $arguments = $node->args;
        if (0 === count($arguments)) {
            return null;
        }

        $firstArgumentValue = $this->getValue($arguments[0]->value);
        if (!is_string($firstArgumentValue)) {
            return null;
        }

        if (false === strpos($firstArgumentValue, '@')) {
            return $this->refactorToNativeFunction($firstArgumentValue);
        }

        return $this->refactorToEmailConcatWithNativeFunction($firstArgumentValue);
    }

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use native function idn_to_ascii instead of GeneralUtility::idnaEncode', [
            new CodeSample(
                <<<'PHP'
$domain = GeneralUtility::idnaEncode('domain.com');
$email = GeneralUtility::idnaEncode('email@domain.com');
PHP
                ,
                <<<'PHP'
$domain = idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
$email = 'email@' . idn_to_ascii('domain.com', IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
PHP
            ),
        ]);
    }

    private function refactorToNativeFunction(string $value): FuncCall
    {
        return $this->createFuncCall('idn_to_ascii', [
            new String_($value),
            new ConstFetch(new Name('IDNA_DEFAULT')),
            new ConstFetch(new Name('INTL_IDNA_VARIANT_UTS46')),
        ]);
    }

    private function refactorToEmailConcatWithNativeFunction(string $value): Concat
    {
        [$email, $domain] = explode('@', $value, 2);

        return new Concat(new String_($email . '@'), $this->refactorToNativeFunction($domain));
    }
}
