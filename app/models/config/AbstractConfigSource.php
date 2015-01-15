<?php

namespace App\Models\Config;

/*
 * Abstrsact Class for Configuration Source
 */

abstract class AbstractConfigSource {

    /**
     * Specified Parameters
     * 
     * @var array 
     */
    protected $_params;

    /**
     * Configiration Orgin
     * 
     * @var string 
     */
    private $origin;

    /**
     * Configuration
     * 
     * @var array 
     */
    private $config;

    /**
     * Constructor
     * 
     * @param string $origin
     * @param array $params
     */
    public function __construct($origin, array $params = null) {
        $this->setOrigin($origin);
        $this->_setParams($params);
    }

    /**
     * Returns All Parameters
     * 
     * @return array
     */
    public function _getParams() {
        return $this->_params;
    }

    /**
     * Returns Specified Origin
     * 
     * @return string
     */
    public function getOrigin() {
        return $this->origin;
    }

    /**
     * Retruns Configuration
     * 
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Setting Parameters
     * 
     * @param array $_requiredParams
     */
    public function _setParams($_requiredParams) {
        $this->_params = $_requiredParams;
    }

    /**
     * Setting Origin
     * 
     * @param string $origin
     */
    public function setOrigin($origin) {
        $this->origin = $origin;
    }

    /**
     * Setting Configuration
     * 
     * @param array $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * Returns all index-values 
     * 
     * @return array
     */
    protected function getAllParamIndexes() {
        $output = array();
        foreach ($this->_params as $value) {
            $output[$value] = $this->retrieve($value);
        }
        return $output;
    }

    /**
     *  Retrieve specified indexes
     * 
     * @param string $index
     * @return mixed
     */
    protected abstract function retrieve($index);
}
