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
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.0/Deprecation-87894-GeneralUtilityidnaEncode.html
 */
final class RefactorIdnaEncodeMethodToNativeFunctionRector extends AbstractRector
{
    /**
     * @inheritDoc
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @var Node|StaticCall
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

        $firstArgument = array_shift($arguments);

        $value = $this->getValue($firstArgument->value);

        if (!is_string($value)) {
            return null;
        }

        if (false === strpos($value, '@')) {
            return $this->refactorToNativeFunction($value);
        }

        return $this->refactorToEmailConcatWithNativeFunction($value);
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
        return $this->createFunction('idn_to_ascii', [
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
