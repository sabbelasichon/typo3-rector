<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\TYPO310\v4;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/TYPO3/typo3/tree/v10.4.10/typo3/sysext/core/Resources/Public/Icons/T3Icons/svgs
 * @see \Ssch\TYPO3Rector\Tests\Rector\v10\v4\UseIconsFromSubFolderInIconRegistryRector\UseIconsFromSubFolderInIconRegistryRectorTest
 */
final class UseIconsFromSubFolderInIconRegistryRector extends AbstractRector
{
    /**
     * @var string
     */
    private const SOURCE = 'source';

    /**
     * @readonly
     */
    private ValueResolver $valueResolver;

    public function __construct(ValueResolver $valueResolver)
    {
        $this->valueResolver = $valueResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->nodeTypeResolver->isMethodStaticCallOrClassMethodObjectType(
            $node,
            new ObjectType('TYPO3\CMS\Core\Imaging\IconRegistry')
        )) {
            return null;
        }

        if (! $this->isName($node->name, 'registerIcon')) {
            return null;
        }

        if (! $this->isSvgIconProvider($node)) {
            return null;
        }

        $options = $this->valueResolver->getValue($node->args[2]->value);

        if (! is_array($options)) {
            return null;
        }

        if (! array_key_exists(self::SOURCE, $options)) {
            return null;
        }

        $source = (string) $options[self::SOURCE];
        if (! str_starts_with($source, 'typo3/sysext/core/Resources/Public/Icons/T3Icons/content/')) {
            return null;
        }

        $options[self::SOURCE] = Strings::replace(
            $source,
            '#typo3/sysext/core/Resources/Public/Icons/T3Icons/content/#i',
            'typo3/sysext/core/Resources/Public/Icons/T3Icons/svgs/content/'
        );

        $node->args[2]->value = $this->nodeFactory->createArray($options);

        return null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Use icons from subfolder in IconRegistry', [
            new CodeSample(
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
        ->registerIcon(
            'apps-pagetree-reference',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/content/content-text.svg',
            ]
        );
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class)
        ->registerIcon(
            'apps-pagetree-reference',
            TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            [
                'source' => 'typo3/sysext/core/Resources/Public/Icons/T3Icons/svgs/content/content-text.svg',
            ]
        );
CODE_SAMPLE
            ),
        ]);
    }

    private function isSvgIconProvider(MethodCall $methodCall): bool
    {
        $iconProviderClassName = $this->valueResolver->getValue($methodCall->args[1]->value);

        if ($iconProviderClassName === null) {
            return false;
        }

        $iconProviderClassNameObjectType = new ObjectType($iconProviderClassName);

        return $iconProviderClassNameObjectType->equals(
            new ObjectType('TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider')
        );
    }
}
