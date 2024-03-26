# TYPO3 HTML Sanitizer notices for upgrading

## v2.1.0

* deprecated `\TYPO3\HtmlSanitizer\Behavior\NodeException::withNode(?DOMNode $node)`,
  use `\TYPO3\HtmlSanitizer\Behavior\NodeException::withDomNode(?DOMNode $domNode)` instead
* deprecated `\TYPO3\HtmlSanitizer\Behavior\NodeException::getNode()`,
  use `\TYPO3\HtmlSanitizer\Behavior\NodeException::getDomNode()` instead
* deprecated property `\TYPO3\HtmlSanitizer\Sanitizer::$root`, superfluous - don't use it anymore
* requirement to provide instance of `\TYPO3\HtmlSanitizer\Behavior` when creating a
  new instance of `\TYPO3\HtmlSanitizer\Sanitizer` (for backward compatibility, this
  is not a hard requirement yet, but already issue an `E_USER_DEPRECATED` PHP error),
  adjust to use `new Sanitizer($behavior, ...$visitors)`
