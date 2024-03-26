<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Variables;

/**
 * Extracts variables from arrays/objects by use
 * of array accessing and basic getter methods.
 *
 * @deprecated Will be removed in Fluid 3.0
 */
class VariableExtractor
{
    public const ACCESSOR_ARRAY = 'array';
    public const ACCESSOR_GETTER = 'getter';
    public const ACCESSOR_ASSERTER = 'asserter';
    public const ACCESSOR_PUBLICPROPERTY = 'public';

    /**
     * Static interface for instantiating and extracting
     * in a single operation. Delegates to getByPath.
     *
     * @param mixed $subject
     * @param string $propertyPath
     * @param array $accessors
     * @return mixed
     */
    public static function extract($subject, $propertyPath, array $accessors = [])
    {
        $extractor = new self();
        return $extractor->getByPath($subject, $propertyPath, $accessors);
    }

    /**
     * Static interface for instanciating and extracting
     * accessors for each segment of the path.
     *
     * @param VariableProviderInterface $subject
     * @param string $propertyPath
     * @return mixed
     */
    public static function extractAccessors($subject, $propertyPath)
    {
        $extractor = new self();
        return $extractor->getAccessorsForPath($subject, $propertyPath);
    }

    /**
     * Extracts a variable by path, recursively, from the
     * subject pass in argument. This implementation supports
     * recursive variable references by using {} around sub-
     * references, e.g. "array.{index}" will first get the
     * "array" variable, then resolve the "index" variable
     * before using the value of "index" as name of the property
     * to return. So:
     *
     * $subject = array('foo' => array('bar' => 'baz'), 'key' => 'bar')
     * $propertyPath = 'foo.{key}';
     * $result = ...getByPath($subject, $propertyPath);
     * // $result value is "baz", because $subject['foo'][$subject['key']] = 'baz';
     *
     * @param mixed $subject
     * @param string $propertyPath
     * @param array $accessors
     * @return mixed
     */
    public function getByPath($subject, $propertyPath, array $accessors = [])
    {
        if ($subject instanceof StandardVariableProvider) {
            return $subject->getByPath($propertyPath);
        }

        $propertyPath = $this->resolveSubVariableReferences($subject, $propertyPath);
        $propertyPathSegments = explode('.', $propertyPath);
        foreach ($propertyPathSegments as $index => $pathSegment) {
            $accessor = isset($accessors[$index]) ? $accessors[$index] : null;
            $subject = $this->extractSingleValue($subject, $pathSegment, $accessor);
            if ($subject === null) {
                break;
            }
        }
        return $subject;
    }

    /**
     * @param VariableProviderInterface $subject
     * @param string $propertyPath
     * @return array
     */
    public function getAccessorsForPath($subject, $propertyPath)
    {
        $accessors = [];
        $propertyPathSegments = explode('.', $propertyPath);
        foreach ($propertyPathSegments as $index => $pathSegment) {
            $accessor = $this->detectAccessor($subject, $pathSegment);
            if ($accessor === null) {
                // Note: this may include cases of sub-variable references. When such
                // a reference is encountered the accessor chain is stopped and new
                // accessors will be detected for the sub-variable and all following
                // path segments since the variable is now fully dynamic.
                break;
            }
            $accessors[] = $accessor;
            $subject = $this->extractSingleValue($subject, $pathSegment);
        }
        return $accessors;
    }

    /**
     * @param mixed $subject
     * @param string $propertyPath
     * @return string
     */
    protected function resolveSubVariableReferences($subject, $propertyPath)
    {
        if (strpos($propertyPath, '{') !== false) {
            preg_match_all('/(\{.*\})/', $propertyPath, $matches);
            foreach ($matches[1] as $match) {
                $subPropertyPath = substr($match, 1, -1);
                $propertyPath = str_replace($match, $this->getByPath($subject, $subPropertyPath), $propertyPath);
            }
        }
        return $propertyPath;
    }

    /**
     * Extracts a single value from an array or object.
     *
     * @param mixed $subject
     * @param string $propertyName
     * @param string|null $accessor
     * @return mixed
     */
    protected function extractSingleValue($subject, $propertyName, $accessor = null)
    {
        if (!$accessor || !$this->canExtractWithAccessor($subject, $propertyName, $accessor)) {
            $accessor = $this->detectAccessor($subject, $propertyName);
        }
        return $this->extractWithAccessor($subject, $propertyName, $accessor);
    }

    /**
     * Returns TRUE if the data type of $subject is potentially compatible
     * with the $accessor.
     *
     * @param mixed $subject
     * @param string $propertyName
     * @param string $accessor
     * @return bool
     */
    protected function canExtractWithAccessor($subject, $propertyName, $accessor)
    {
        $class = is_object($subject) ? get_class($subject) : false;
        if ($accessor === self::ACCESSOR_ARRAY) {
            return is_array($subject) || ($subject instanceof \ArrayAccess && $subject->offsetExists($propertyName));
        }
        if ($accessor === self::ACCESSOR_GETTER) {
            return $class !== false && method_exists($subject, 'get' . ucfirst($propertyName));
        }
        if ($accessor === self::ACCESSOR_ASSERTER) {
            return $class !== false && $this->isExtractableThroughAsserter($subject, $propertyName);
        }
        if ($accessor === self::ACCESSOR_PUBLICPROPERTY) {
            return $class !== false && property_exists($subject, $propertyName);
        }
        return false;
    }

    /**
     * @param mixed $subject
     * @param string $propertyName
     * @param string $accessor
     * @return mixed
     */
    protected function extractWithAccessor($subject, $propertyName, $accessor)
    {
        if ($accessor === self::ACCESSOR_ARRAY && is_array($subject) && array_key_exists($propertyName, $subject)
            || $subject instanceof \ArrayAccess && $subject->offsetExists($propertyName)
        ) {
            return $subject[$propertyName];
        }
        if (is_object($subject)) {
            if ($accessor === self::ACCESSOR_GETTER) {
                return call_user_func_array([$subject, 'get' . ucfirst($propertyName)], []);
            }
            if ($accessor === self::ACCESSOR_ASSERTER) {
                return $this->extractThroughAsserter($subject, $propertyName);
            }
            if ($accessor === self::ACCESSOR_PUBLICPROPERTY && property_exists($subject, $propertyName)) {
                return $subject->$propertyName;
            }
        }
        return null;
    }

    /**
     * Detect which type of accessor to use when extracting
     * $propertyName from $subject.
     *
     * @param mixed $subject
     * @param string $propertyName
     * @return string|null
     */
    protected function detectAccessor($subject, $propertyName)
    {
        if (is_array($subject) || ($subject instanceof \ArrayAccess && $subject->offsetExists($propertyName))) {
            return self::ACCESSOR_ARRAY;
        }
        if (is_object($subject)) {
            $upperCasePropertyName = ucfirst($propertyName);
            $getter = 'get' . $upperCasePropertyName;
            if (is_callable([$subject, $getter])) {
                return self::ACCESSOR_GETTER;
            }
            if ($this->isExtractableThroughAsserter($subject, $propertyName)) {
                return self::ACCESSOR_ASSERTER;
            }
            if (property_exists($subject, $propertyName)) {
                return self::ACCESSOR_PUBLICPROPERTY;
            }
        }

        return null;
    }

    /**
     * Tests whether a property can be extracted through `is*` or `has*` methods.
     *
     * @param mixed $subject
     * @param string $propertyName
     * @return bool
     */
    protected function isExtractableThroughAsserter($subject, $propertyName)
    {
        return method_exists($subject, 'is' . ucfirst($propertyName))
            || method_exists($subject, 'has' . ucfirst($propertyName));
    }

    /**
     * Extracts a property through `is*` or `has*` methods.
     *
     * @param object $subject
     * @param string $propertyName
     * @return mixed
     */
    protected function extractThroughAsserter($subject, $propertyName)
    {
        if (method_exists($subject, 'is' . ucfirst($propertyName))) {
            return call_user_func_array([$subject, 'is' . ucfirst($propertyName)], []);
        }

        return call_user_func_array([$subject, 'has' . ucfirst($propertyName)], []);
    }
}
