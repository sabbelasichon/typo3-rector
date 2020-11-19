<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v9\v0;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/9.0/Deprecation-82426-Typo3-pagetreeNavigationComponentName.html
 */
final class UseNewComponentIdForPageTreeRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ExtensionUtility::class)) {
            return null;
        }

        if (! $this->isName($node->name, 'registerModule')) {
            return null;
        }

        if (! isset($node->args[1], $node->args[5])) {
            return null;
        }

        if (! $this->isValue($node->args[1]->value, 'web')) {
            return null;
        }

        $moduleConfiguration = $node->args[5]->value;

        if (! $moduleConfiguration instanceof Array_) {
            return null;
        }

        foreach ($moduleConfiguration->items as $item) {
            if (! $item instanceof ArrayItem) {
                continue;
            }

            if (null === $item->key) {
                continue;
            }

            if ('navigationComponentId' !== $this->getValue($item->key)) {
                continue;
            }
            $item->value = new String_('TYPO3/CMS/Backend/PageTree/PageTreeElement');
        }

        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Use TYPO3/CMS/Backend/PageTree/PageTreeElement instead of typo3-pagetree', [
            new CodeSample(<<<'PHP'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
      'TYPO3.CMS.Workspaces',
      'web',
      'workspaces',
      'before:info',
      [
          // An array holding the controller-action-combinations that are accessible
          'Review' => 'index,fullIndex,singleIndex',
          'Preview' => 'index,newPage'
      ],
      [
          'access' => 'user,group',
          'icon' => 'EXT:workspaces/Resources/Public/Icons/module-workspaces.svg',
          'labels' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_mod.xlf',
          'navigationComponentId' => 'typo3-pagetree'
      ]
  );
PHP
                , <<<'PHP'
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
      'TYPO3.CMS.Workspaces',
      'web',
      'workspaces',
      'before:info',
      [
          // An array holding the controller-action-combinations that are accessible
          'Review' => 'index,fullIndex,singleIndex',
          'Preview' => 'index,newPage'
      ],
      [
          'access' => 'user,group',
          'icon' => 'EXT:workspaces/Resources/Public/Icons/module-workspaces.svg',
          'labels' => 'LLL:EXT:workspaces/Resources/Private/Language/locallang_mod.xlf',
          'navigationComponentId' => 'TYPO3/CMS/Backend/PageTree/PageTreeElement'
      ]
  );
PHP
            ),
        ]);
    }
}
