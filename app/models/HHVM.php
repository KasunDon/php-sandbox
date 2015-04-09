<?php

namespace App\Models;

/**
 * HHVM sandbox
 */
class HHVM extends Sandbox{
    
    /**
     * Type
     * 
     * @var string 
     */
    protected $_type = 'HHVM';
    
    /**
     * Constructor
     * 
     * @param string $sourceCode
     * @param string $version
     */
    public function __construct($sourceCode, $version) {
        parent::__construct($version, $sourceCode);
        $this->setServers(\App::make('app.config.env')->VIRTSTORE);
    }
    
    /**
     * Overriden - Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {

        return shell_exec("docker run -v {$files['sandbox']}:{$files['sandbox']}"
        . " -w {$files['sandbox']} kasundon/phpbox_hhvm:" . $this->getVersion() . " hhvm {$files['php']} 2>&1");
    }

    /**
     * Overriding - Returns resource address
     * 
     * @param string $route
     * @return string
     */
    protected function _getAddress($route) {
        return "http://$route/api/hhvm/" . $this->getVersion();
    }

    /**
     * Overriden - Prepares payload
     * 
     * @return array
     */
    protected function _getPayload() {
        return array('v' => $this->getVersion(), 'code' => $this->getSourceCode());
    }

    /**
     * Overriden - Settings for sandbox
     * 
     * @param array $files
     * @throws \App\Exception\FileCopyException
     */
    protected function _sandboxSettings($files) {
        return $files;
    }
}