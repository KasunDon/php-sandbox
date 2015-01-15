<?php

namespace App\Models\Config;

use App\Exception\InvalidConfigKeyException;
use App\Models\Config\EnvConfig;

/*
 * Config Class 
 */

class Config {

    /**
     * Class Constants
     */
    const ENVIRONMENT_VARS = 'environment-variables';

    /**
     * Required Environment Variables
     * 
     * @var array 
     */
    protected static $ENV_VARS = array(
        'INI_FILE', 'SANDBOX', 'PHP_SANDBOX_VERSIONS',
        'PHP_SANDBOX_PATH'
    );

    /**
     * Configuration
     * 
     * @var array 
     */
    protected $_configuration = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->_setConfiguration();
    }

    /**
     * Initialize configurations
     */
    protected function _setConfiguration() {
        $this->_configuration[self::ENVIRONMENT_VARS] = new EnvConfig(self::ENVIRONMENT_VARS, self::$ENV_VARS);
    }

    /**
     * Get configuration by config key
     * 
     * @param type $configKey
     * @return ConfigSourceInterface
     * @throws Exception
     */
    public function get($configKey) {

        if (empty($configKey) || empty($this->_configuration[$configKey])) {
            throw new InvalidConfigKeyException();
        }

        return $this->_configuration[$configKey];
    }

    /**
     * Reurtns specified configuration instances
     * 
     * @param type $type
     * @return type
     */
    public static function pull($type) {
        $self = new self();
        return $self->get($type);
    }

}
