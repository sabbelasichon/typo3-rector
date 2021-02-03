<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v10\v4;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.4/Deprecation-90147-UnifiedFileNameValidator.html
 */
final class UnifiedFileNameValidatorRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [ConstFetch::class, StaticCall::class];
    }

    /**
     * @param ConstFetch|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if ($node instanceof StaticCall && $this->isMethodVerifyFilenameAgainstDenyPattern($node)) {
            return $this->nodeFactory->createMethodCall(
                $this->nodeFactory->createStaticCall(GeneralUtility::class, 'makeInstance', [
                    $this->nodeFactory->createClassConstReference(FileNameValidator::class),
                ]),
                'isValid',
                $node->args
            );
        }

        if ($this->isConstFileDenyPatternDefault($node)) {
            return $this->nodeFactory->createClassConstFetch(FileNameValidator::class, 'DEFAULT_FILE_DENY_PATTERN');
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'GeneralUtility::verifyFilenameAgainstDenyPattern GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)',
            [
                new CodeSample(
                    <<<'PHP'
use TYPO3\CMS\Core\Utility\GeneralUtility;

$filename = 'somefile.php';
if(!GeneralUtility::verifyFilenameAgainstDenyPattern($filename)) {
}

if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FILE_DENY_PATTERN_DEFAULT)
{
}
PHP
                    ,
                    <<<'PHP'
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$filename = 'somefile.php';
if(!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($filename)) {
}

if ($GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern'] != FileNameValidator::DEFAULT_FILE_DENY_PATTERN)
{
}
PHP
                ),
            ]
        );
    }

    /**
     * @param ConstFetch|StaticCall $node
     */
    public function isMethodVerifyFilenameAgainstDenyPattern(Node $node): bool
    {
        return $node instanceof StaticCall && $this->isMethodStaticCallOrClassMethodObjectType(
            $node,
            GeneralUtility::class
        ) && $this->isName($node->name, 'verifyFilenameAgainstDenyPattern');
    }

    /**
     * @param ConstFetch|StaticCall $node
     */
    private function shouldSkip(Node $node): bool
    {
        if ($this->isMethodVerifyFilenameAgainstDenyPattern($node)) {
            return false;
        }

        return ! $this->isConstFileDenyPatternDefault($node);
    }

    /**
     * @param ConstFetch|StaticCall $node
     */
    private function isConstFileDenyPatternDefault(Node $node): bool
    {
        return $node instanceof ConstFetch && $this->isName($node->name, 'FILE_DENY_PATTERN_DEFAULT');
    }
}
