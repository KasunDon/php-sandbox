<?php

namespace App\Models\Config;

/**
 * Environment Variable Configuration Class
 */
class EnvConfig extends AbstractConfigSource implements ConfigSourceInterface {

    /**
     * Constructor
     * 
     * @param string $origin
     * @param array $params
     */
    public function __construct($origin, array $params = null) {
        parent::__construct($origin, $params);

        $this->load();
    }
    
    /**
     *  Retrieve specified indexes
     * 
     * @param string $index
     * @return mixed
     */
    protected function retrieve($index) {
        if (!empty($index)) {
            $value = getenv($index);
            return ($value === false) ? null : $value;
        } else {
            return $this->getAllParamIndexes();
        }
    }
    
    /**
     * Loading Configuration
     */
    public function load() {
        $this->setConfig($this->getAllParamIndexes());
    }

    /**
     *  Magic __get() overridding for access configuration
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        $config = $this->getConfig();
        return isset($config[$name]) ? $config[$name] : false;
    }

}
