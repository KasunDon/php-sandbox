<?php

namespace App\Models;

/**
 * Class for handle Vulcan Logic Disassembler (VLD)
 */
class VLD extends Sandbox {

    /**
     * Constructor
     * 
     * @param string $sourceCode
     * @param string $version
     */
    public function __construct($sourceCode) {
        parent::__construct('5.5.6', $sourceCode);
        $this->setServers(\App::make('app.config.env')->VIRTSTORE);
    }
    
    /**
     * Overriding - Returns resource address
     * 
     * @param string $route
     * @return string
     */
    protected function _getAddress($route) {
        return "http://$route/api/vld-data";
    }

    /**
     * Overriding - Prepares payload
     * 
     * @return array
     */
    protected function _getPayload() {
        return array('code' => $this->getSourceCode());
    }

    /**
     * Overriden - Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {

        return shell_exec("docker run -v {$files['sandbox']}:{$files['sandbox']}"
        . " -w {$files['sandbox']} sandbox:core php -c {$files['ini']} -dextension=vld.so "
        . "-dvld.active=1 -dvld.verbosity=1 -dvld.execute=0 {$files['php']} 2>&1");
    }
    
    /**
     * Settings for sandbox
     * 
     * @param array $files
     * @throws \App\Exception\FileCopyException
     */
    protected function _sandboxSettings($files) {
        return $files;
    }

    /**
     * Allowing statically access self instance
     * 
     * @return \self
     */
    public static function init($source) {
        return new self($source);
    }

}
