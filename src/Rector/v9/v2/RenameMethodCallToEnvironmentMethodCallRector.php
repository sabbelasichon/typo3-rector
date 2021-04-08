<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v2;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.2/Feature-84153-IntroduceAGenericEnvironmentClass.html
 */
final class RenameMethodCallToEnvironmentMethodCallRector extends AbstractRector
{
    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns method call names to new ones from new Environment API.', [
            new CodeSample(<<<'CODE_SAMPLE'
Bootstrap::usesComposerClassLoading();
GeneralUtility::getApplicationContext();
EnvironmentService::isEnvironmentInCliMode();
CODE_SAMPLE
, <<<'CODE_SAMPLE'
Environment::isComposerMode();
Environment::getContext();
Environment::isCli();
CODE_SAMPLE
),
        ]);
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<class-string<Node>>
=======
=======
>>>>>>> da7142f... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
>>>>>>> f7cbd4b... make PHPStan smarted on getNodeTypes()
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
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
        $className = $this->getName($node->class);
        $methodName = $this->getName($node->name);
        if (Bootstrap::class === $className && 'usesComposerClassLoading' === $methodName) {
            return $this->nodeFactory->createStaticCall(Environment::class, 'isComposerMode');
        }
        if (GeneralUtility::class === $className && 'getApplicationContext' === $methodName) {
            return $this->nodeFactory->createStaticCall(Environment::class, 'getContext');
        }
        if (EnvironmentService::class === $className && 'isEnvironmentInCliMode' === $methodName) {
            return $this->nodeFactory->createStaticCall(Environment::class, 'isCli');
        }
        return null;
    }
}
