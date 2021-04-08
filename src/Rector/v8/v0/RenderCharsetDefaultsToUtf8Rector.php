<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Ssch\TYPO3Rector\Helper\Typo3NodeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.0/Breaking-73794-RenderCharsetOptionRemoved.html
 */
final class RenderCharsetDefaultsToUtf8Rector extends AbstractRector
{
    /**
     * @var Typo3NodeResolver
     */
    private $typo3NodeResolver;

    public function __construct(Typo3NodeResolver $typo3NodeResolver)
    {
        $this->typo3NodeResolver = $typo3NodeResolver;
    }

    /**
<<<<<<< HEAD
     * @return array<class-string<\PhpParser\Node>>
     */

    /**
=======
>>>>>>> 8781ff4... rename AbstractCommunityRectorTestCase to AbstractRectorTestCase
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        if (! $this->isName($node->name, 'renderCharset')) {
            return null;
        }

        return new String_('utf-8');
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('The property $TSFE->renderCharset is now always set to utf-8', [
            new CodeSample(<<<'CODE_SAMPLE'
mb_strlen(trim($this->gp[$this->formFieldName]), $GLOBALS['TSFE']->renderCharset) > 0;
CODE_SAMPLE
                , <<<'CODE_SAMPLE'
mb_strlen(trim($this->gp[$this->formFieldName]), 'utf-8') > 0;
CODE_SAMPLE
            ),
        ]);
    }

    private function shouldSkip(PropertyFetch $node): bool
    {
        if ($this->isObjectType($node->var, TypoScriptFrontendController::class)) {
            return false;
        }
        return ! $this->typo3NodeResolver->isPropertyFetchOnAnyPropertyOfGlobals(
            $node,
            Typo3NodeResolver::TYPO_SCRIPT_FRONTEND_CONTROLLER
        );
    }
}
