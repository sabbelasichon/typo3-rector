<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;

$compositeExpressionAND = new CompositeExpression(CompositeExpression::TYPE_AND, []);
$compositeExpressionOR = new CompositeExpression(CompositeExpression::TYPE_OR, []);

$compositeExpressionAND = new CompositeExpression('AND', []);
$compositeExpressionOR = new CompositeExpression('OR', []);

$foo = ['bar'];
$compositeExpressionAND = new CompositeExpression(CompositeExpression::TYPE_AND, $foo);
$compositeExpressionOR = new CompositeExpression(CompositeExpression::TYPE_OR, $foo);

?>
-----
<?php

namespace Ssch\TYPO3Rector\Tests\Rector\v12\v0\typo3\UseCompositeExpressionStaticMethodsRector\Fixture;

use TYPO3\CMS\Core\Database\Query\Expression\CompositeExpression;

$compositeExpressionAND = CompositeExpression::and([]);
$compositeExpressionOR = CompositeExpression::or([]);

$compositeExpressionAND = CompositeExpression::and([]);
$compositeExpressionOR = CompositeExpression::or([]);

$foo = ['bar'];
$compositeExpressionAND = CompositeExpression::and($foo);
$compositeExpressionOR = CompositeExpression::or($foo);

?>
