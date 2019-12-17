<?php

use TYPO3\CMS\Fluid\Core\Compiler\TemplateCompiler;
use TYPO3\CMS\Fluid\Core\Exception;
use TYPO3\CMS\Fluid\Core\Parser\InterceptorInterface;
use TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\NodeInterface;
use TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\RootNode;
use TYPO3\CMS\Fluid\Core\Parser\SyntaxTree\ViewHelperNode;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\Variables\CmsVariableProvider;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\PostParseInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;
use TYPO3\CMS\Fluid\Core\ViewHelper\TemplateVariableContainer;
use TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer;
use TYPO3\CMS\Fluid\View\Exception\InvalidSectionException;
use TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException;

return [
    // Base classes removed in TYPO3 v9
    AbstractViewHelper::class => \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper::class,
    AbstractConditionViewHelper::class => \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper::class,
    AbstractTagBasedViewHelper::class => \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper::class,

    // Compiler/parser related aliases
    TemplateCompiler::class => \TYPO3Fluid\Fluid\Core\Compiler\TemplateCompiler::class,
    InterceptorInterface::class => \TYPO3Fluid\Fluid\Core\Parser\InterceptorInterface::class,
    NodeInterface::class => \TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface::class,
    'TYPO3\\CMS\\Fluid\\Core\\Parser\\SyntaxTree\\AbstractNode' => \TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode::class,
    RenderingContextInterface::class => \TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface::class,
    ViewHelperInterface::class => \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface::class,
    ChildNodeAccessInterface::class => \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface::class,
    CompilableInterface::class => \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface::class,
    PostParseInterface::class => \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperInterface::class,

    // Fluid-specific errors
    Exception::class => \TYPO3Fluid\Fluid\Core\Exception::class,
    \TYPO3\CMS\Fluid\Core\ViewHelper\Exception::class => \TYPO3Fluid\Fluid\Core\ViewHelper\Exception::class,
    InvalidVariableException::class => \TYPO3Fluid\Fluid\Core\Exception::class,
    \TYPO3\CMS\Fluid\View\Exception::class => \TYPO3Fluid\Fluid\View\Exception::class,
    InvalidSectionException::class => \TYPO3Fluid\Fluid\View\Exception\InvalidSectionException::class,
    InvalidTemplateResourceException::class => \TYPO3Fluid\Fluid\View\Exception\InvalidTemplateResourceException::class,

    // Fluid variable containers, ViewHelpers, interfaces
    RootNode::class => \TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\RootNode::class,
    ViewHelperNode::class => \TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\ViewHelperNode::class,
    'TYPO3\\CMS\\Fluid\\Core\\ViewHelper\\ArgumentDefinition' => \TYPO3Fluid\Fluid\Core\ViewHelper\ArgumentDefinition::class,
    TemplateVariableContainer::class => \TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider::class,
    ViewHelperVariableContainer::class => \TYPO3Fluid\Fluid\Core\ViewHelper\ViewHelperVariableContainer::class,
    CmsVariableProvider::class => \TYPO3Fluid\Fluid\Core\Variables\StandardVariableProvider::class,

    // Semi API level classes; mainly used in unit tests
    TagBuilder::class => \TYPO3Fluid\Fluid\Core\ViewHelper\TagBuilder::class,
];
