<?php

declare(strict_types=1);

// see https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata
namespace PHPSTORM_META;

// $container->get(Type::class) → instance of "Type"
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;

override(\Psr\Container\ContainerInterface::get(0), type(0));

expectedArguments(
    \PHPStan\PhpDocParser\Ast\Node::getAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);

expectedArguments(
    \PHPStan\PhpDocParser\Ast\NodeAttributes::getAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);

expectedArguments(
    \PHPStan\PhpDocParser\Ast\Node::hasAttribute(),
    0,
    PhpDocAttributeKey::START_AND_END,
    PhpDocAttributeKey::LAST_PHP_DOC_TOKEN_POSITION,
    PhpDocAttributeKey::PARENT,
    PhpDocAttributeKey::ORIG_NODE,
    PhpDocAttributeKey::RESOLVED_CLASS,
);


// PhpStorm 2019.1 - add argument autocomplete
// https://blog.jetbrains.com/phpstorm/2019/02/new-phpstorm-meta-php-features/
expectedArguments(
    \PhpParser\Node::getAttribute(),
    0,
    \Rector\NodeTypeResolver\Node\AttributeKey::SCOPE,
    \Rector\NodeTypeResolver\Node\AttributeKey::REPRINT_RAW_VALUE,
    \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE,
    \Rector\NodeTypeResolver\Node\AttributeKey::IS_UNREACHABLE,
    \Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO,
    \Rector\NodeTypeResolver\Node\AttributeKey::KIND,
    \Rector\NodeTypeResolver\Node\AttributeKey::IS_REGULAR_PATTERN,
    \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME,
    \Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS,
    \Rector\NodeTypeResolver\Node\AttributeKey::VIRTUAL_NODE,
    \Rector\NodeTypeResolver\Node\AttributeKey::RAW_VALUE,
);

expectedArguments(
    \PhpParser\Node::setAttribute(),
    0,
    \Rector\NodeTypeResolver\Node\AttributeKey::SCOPE,
    \Rector\NodeTypeResolver\Node\AttributeKey::REPRINT_RAW_VALUE,
    \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE,
    \Rector\NodeTypeResolver\Node\AttributeKey::IS_UNREACHABLE,
    \Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO,
    \Rector\NodeTypeResolver\Node\AttributeKey::KIND,
    \Rector\NodeTypeResolver\Node\AttributeKey::IS_REGULAR_PATTERN,
    \Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NAME,
    \Rector\NodeTypeResolver\Node\AttributeKey::COMMENTS,
    \Rector\NodeTypeResolver\Node\AttributeKey::VIRTUAL_NODE,
    \Rector\NodeTypeResolver\Node\AttributeKey::RAW_VALUE,
);
