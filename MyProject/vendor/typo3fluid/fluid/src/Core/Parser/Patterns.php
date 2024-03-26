<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Parser;

/**
 * Class Patterns
 */
abstract class Patterns
{
    public const NAMESPACEPREFIX = 'http://typo3.org/ns/';
    public const NAMESPACESUFFIX = '/ViewHelpers';

    /**
     * This regular expression splits the input string at all dynamic tags, AND
     * on all <![CDATA[...]]> sections.
     */
    public static $SPLIT_PATTERN_TEMPLATE_DYNAMICTAGS = '/
		(
			(?: <\/?                                      # Start dynamic tags
					(?:(?:[a-zA-Z0-9\\.]*):[a-zA-Z0-9\\.]+)  # A tag consists of the namespace prefix and word characters
					(?:                                   # Begin tag arguments
						\s*[a-zA-Z0-9:-]+                 # Argument Keys
						\s*
						=                                 # =
						\s*
						(?>                               # either... If we have found an argument, we will not back-track (That does the Atomic Bracket)
							"(?:\\\"|[^"])*"              # a double-quoted string
							|\'(?:\\\\\'|[^\'])*\'        # or a single quoted string
						)\s*                              #
					)*                                    # Tag arguments can be replaced many times.
				\s*
				\/?>                                      # Closing tag
			)
			|(?:                                          # Start match CDATA section
				<!\[CDATA\[.*?\]\]>
			)
		)/xs';

    /**
     * This regular expression scans if the input string is a ViewHelper tag
     */
    public static $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG = '/
		^<                                                # A Tag begins with <
		(?P<NamespaceIdentifier>[a-zA-Z0-9\\.]*):         # Then comes the Namespace prefix followed by a :
		(?P<MethodIdentifier>                             # Now comes the Name of the ViewHelper
			[a-zA-Z0-9\\.]+
		)
		(?P<Attributes>                                   # Begin Tag Attributes
			(?:                                           # A tag might have multiple attributes
				\s*
				[a-zA-Z0-9:-]+                            # The attribute name
				\s*
				=                                         # =
				\s*
				(?>                                       # either... # If we have found an argument, we will not back-track (That does the Atomic Bracket)
					"(?:\\\"|[^"])*"                      # a double-quoted string
					|\'(?:\\\\\'|[^\'])*\'                # or a single quoted string
				)                                         #
				\s*
			)*
		)                                                 # End Tag Attributes
		\s*
		(?P<Selfclosing>\/?)                              # A tag might be selfclosing
		>$/x';

    /**
     * This regular expression scans if the input string is a closing ViewHelper
     * tag.
     */
    public static $SCAN_PATTERN_TEMPLATE_CLOSINGVIEWHELPERTAG =
        '/^<\/(?P<NamespaceIdentifier>[a-zA-Z0-9\\.]*):(?P<MethodIdentifier>[a-zA-Z0-9\\.]+)\s*>$/';

    /**
     * This regular expression splits the tag arguments into its parts
     */
    public static $SPLIT_PATTERN_TAGARGUMENTS = '/
		(?:                                              #
			\s*                                          #
			(?P<Argument>                                # The attribute name
				[a-zA-Z0-9:-]+                           #
			)                                            #
			\s*                                          #
			=                                            # =
			\s*                                          #
			(?>                                          # If we have found an argument, we will not back-track (That does the Atomic Bracket)
				(?P<ValueQuoted>                         # either...
					(?:"(?:\\\"|[^"])*")                 # a double-quoted string
					|(?:\'(?:\\\\\'|[^\'])*\')           # or a single quoted string
				)
			)\s*
		)
		/xs';

    /**
     * This pattern detects the escaping modifier
     *
     * @deprecated Unused. Will be removed.
     */
    public static $SCAN_PATTERN_ESCAPINGMODIFIER = '/{escapingEnabled\s*=\s*(?P<enabled>true|false)\s*}/i';

    /**
     * This pattern detects CDATA sections and outputs the text between opening
     * and closing CDATA.
     */
    public static $SCAN_PATTERN_CDATA = '/^<!\[CDATA\[(.*?)\]\]>$/s';

    /**
     * Pattern which splits the shorthand syntax into different tokens. The
     * "shorthand syntax" is everything like {...}
     */
    public static $SPLIT_PATTERN_SHORTHANDSYNTAX = '/
		(
			{                                 # Start of shorthand syntax
				(?:                           # Shorthand syntax is either composed of...
					[a-zA-Z0-9\|\->_:=,.()*+\^\/\%] # Various characters including math operations
					|"(?:\\\"|[^"])*"         # Double-quoted strings
					|\'(?:\\\\\'|[^\'])*\'    # Single-quoted strings
					|(?R)                     # Other shorthand syntaxes inside, albeit not in a quoted string
					|\s+                      # Spaces
				)+
			}                                 # End of shorthand syntax
		)/x';

    /**
     * Pattern which detects the object accessor syntax:
     * {object.some.value}, additionally it detects ViewHelpers like
     * {f:for(param1:bla)} and chaining like
     * {object.some.value -> f:bla.blubb() -> f:bla.blubb2()}
     *
     * THIS IS ALMOST THE SAME AS IN $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
     */
    public static $SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS = '/
		^{                                                  # Start of shorthand syntax
			                                                # A shorthand syntax is either...
			(?P<Object>[a-zA-Z0-9_\-\.\{\}]*)                 # ... an object accessor
			\s*(?P<Delimiter>(?:->|\|)?)\s*

			(?P<ViewHelper>                                 # ... a ViewHelper
				[a-zA-Z0-9\\.]+                             # Namespace prefix of ViewHelper (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
				:
				[a-zA-Z0-9\\.]+                             # Method Identifier (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
				\(                                          # Opening parameter brackets of ViewHelper
					(?P<ViewHelperArguments>                # Start submatch for ViewHelper arguments. This is taken from $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
						(?:
							\s*[a-zA-Z0-9\-_]+              # The keys of the array
							\s*[:=]\s*                      # Key|Value delimiter : or =
							(?:                             # Possible value options:
								"(?:\\\"|[^"])*"            # Double qouoted string
								|\'(?:\\\\\'|[^\'])*\'      # Single quoted string
								|[a-zA-Z0-9\-_.]+           # variable identifiers
								|{(?P>ViewHelperArguments)} # Another sub-array
							)                               # END possible value options
							\s*,?\s*                        # There might be a , to seperate different parts of the array
						)*                                  # The above cycle is repeated for all array elements
					)                                       # End ViewHelper Arguments submatch
				\)                                          # Closing parameter brackets of ViewHelper
			)?
			(?P<AdditionalViewHelpers>                      # There can be more than one ViewHelper chained, by adding more -> and the ViewHelper (recursively)
				(?:
					\s*(?:->|\|)\s*
					(?P>ViewHelper)
				)*
			)
		}$/x';

    /**
     * THIS IS ALMOST THE SAME AS $SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS
     */
    public static $SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER = '/

		(?P<NamespaceIdentifier>[a-zA-Z0-9\\.]+)    # Namespace prefix of ViewHelper (as in $SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG)
		:
		(?P<MethodIdentifier>[a-zA-Z0-9\\.]+)
		\(                                          # Opening parameter brackets of ViewHelper
			(?P<ViewHelperArguments>                # Start submatch for ViewHelper arguments. This is taken from $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS
				(?:
					\s*[a-zA-Z0-9\-_]+              # The keys of the array
					\s*[:=]\s*                      # Key|Value delimiter : or =
					(?:                             # Possible value options:
						"(?:\\\"|[^"])*"            # Double qouoted string
						|\'(?:\\\\\'|[^\'])*\'      # Single quoted string
						|[a-zA-Z0-9\-_.]+           # variable identifiers
						|{(?P>ViewHelperArguments)} # Another sub-array
					)                               # END possible value options
					\s*,?\s*                        # There might be a , to seperate different parts of the array
				)*                                  # The above cycle is repeated for all array elements
			)                                       # End ViewHelper Arguments submatch
		\)                                          # Closing parameter brackets of ViewHelper
		/x';

    /**
     * Pattern which detects the array/object syntax like in JavaScript, so it
     * detects strings like:
     * {object: value, object2: {nested: array}, object3: "Some string"}
     *
     * THIS IS ALMOST THE SAME AS IN SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS
     */
    public static $SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS = '/^
		(?P<Recursion>                                             # Start the recursive part of the regular expression - describing the array syntax
			{                                                      # Each array needs to start with {
				(?P<Array>                                         # Start sub-match
					(?:
						\s*(
							[a-zA-Z0-9\\-_]+                       # Unquoted key
							|"(?:\\\"|[^"])+"                      # Double quoted key, supporting more characters like dots and square brackets
							|\'(?:\\\\\'|[^\'])+\'                 # Single quoted key, supporting more characters like dots and square brackets
						)
						\s*[:=]\s*                                 # Key|Value delimiter : or =
						(?:                                        # Possible value options:
							"(?:\\\"|[^"])*"                       # Double quoted string
							|\'(?:\\\\\'|[^\'])*\'                 # Single quoted string
							|[a-zA-Z0-9\-_.]+                      # variable identifiers
							|(?P>Recursion)                        # Another sub-array
						)                                          # END possible value options
						\s*,?\s*                                   # There might be a , to separate different parts of the array
					)*                                             # The above cycle is repeated for all array elements
				)                                                  # End array sub-match
			}                                                      # Each array ends with }
		)$/x';

    /**
     * This pattern splits an array into its parts, each part consists of a key and a value.
     * It is quite similar to the pattern above.
     * Note that this pattern can be used on strings with or without surrounding curly brackets.
     */
    public static $SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS = '/
		(?P<ArrayPart>                                                      # Start sub-match of one key and value pair
			(?P<Key>                                                        # The arry key
				 [a-zA-Z0-9_-]+                                             # Unquoted
				|"(?:\\\\"|[^"])+"                                          # Double quoted
				|\'(?:\\\\\'|[^\'])+\'                                      # Single quoted
			)
			\\s*[:=]\\s*                                                    # Key|Value delimiter : or =
			(?:                                                             # BEGIN Possible value options
				(?P<QuotedString>                                           # Quoted string
					 "(?:\\\\"|[^"])*"
					|\'(?:\\\\\'|[^\'])*\'
				)
				|(?P<VariableIdentifier>
					(?:(?=[^,{}\.]*[a-zA-Z])[a-zA-Z0-9_-]*)                 # variable identifiers must contain letters (otherwise they are hardcoded numbers)
					(?:\\.[a-zA-Z0-9_-]+)*                                  # but in sub key access only numbers are fine (foo.55)
				)
				|(?P<Number>[0-9]+(?:\\.[0-9]+)?)                           # A hardcoded Number (also possibly with decimals)
				|\\{\\s*(?P<Subarray>(?:(?P>ArrayPart)\\s*,?\\s*)+)\\s*\\}  # Another sub-array
			)                                                               # END possible value options
		)\\s*(?=\\z|,|\\})                                                  # An array part sub-match ends with either a comma, a closing curly bracket or end of string
	/x';
}
