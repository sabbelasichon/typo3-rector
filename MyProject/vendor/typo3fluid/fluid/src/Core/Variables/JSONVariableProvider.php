<?php

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\Core\Variables;

/**
 * Class JSONVariableProvider
 *
 * VariableProvider capable of using JSON files
 * and streams as data source.
 */
class JSONVariableProvider extends StandardVariableProvider implements VariableProviderInterface
{
    /**
     * @var int
     */
    protected $lastLoaded = 0;

    /**
     * Lifetime of fetched JSON sources before refetch. Using
     * a hard value avoids the need to re-query using HEAD and
     * should allow any HTTPD process to finish in time but make
     * any CLI/infinite running scripts re-fetch JSON after this
     * time has passed.
     *
     * @var int
     */
    protected $ttl = 15;

    /**
     * JSON source. Either a complete JSON string with an object
     * inside, or a reference to a JSON file either local or
     * remote (supporting any stream types PHP supports).
     *
     * @var string
     */
    protected $source;

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $this->load();
        return parent::getAll();
    }

    /**
     * @param string $identifier
     * @return mixed
     */
    public function get($identifier)
    {
        $this->load();
        return parent::get($identifier);
    }

    /**
     * @return array
     */
    public function getAllIdentifiers()
    {
        $this->load();
        return parent::getAllIdentifiers();
    }

    protected function load()
    {
        if ($this->source !== null && time() > ($this->lastLoaded + $this->ttl)) {
            if (!$this->isJSON($this->source)) {
                $source = file_get_contents($this->source);
            } else {
                $source = $this->source;
            }
            $this->variables = json_decode($source, defined('JSON_OBJECT_AS_ARRAY') ? JSON_OBJECT_AS_ARRAY : 1);
            $this->lastLoaded = time();
        }
    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isJSON($string)
    {
        $string = trim($string);
        return $string[0] === '{' && substr($string, -1) === '}';
    }
}
