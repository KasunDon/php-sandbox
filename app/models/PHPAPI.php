<?php

namespace App\Models;

/**
 * Class for PHP Sandbox runtime access
 */
class PHPAPI extends Sandbox { 
  
    /**
     * Constructor
     * 
     * @param string $version
     * @param string $sourceCode
     */
    public function __construct($version, $sourceCode) {
        parent::__construct($version, $sourceCode);
        $this->setServers(\App::make('app.config.env')->PHP_SANDBOX_SERVERS);
    }
    
    /**
     * Overriden - Returns resource address
     * 
     * @param string $route
     * @return string
     */
    protected function _getAddress($route) {
        $version = $this->getVersion();
        return "https://$route/api/php/$version/run";
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
        //copy default php.ini to sandbox
        if (!copy(sprintf(\App::make('app.config.env')->PHP_SANDBOX_PATH, $this->getVersion()), $files['ini'])) {
            throw new \App\Exception\FileCopyException();
        }

        $ini_settings = "\n" . file_get_contents(\App::make('app.config.env')->INI_FILE) . "\n";
        $ini_settings .= 'open_basedir = "' . $files['sandbox'] . '"' . "\n";

        //adding custom ini settings to temp ini file
        file_put_contents($files['ini'], $ini_settings, FILE_APPEND);  
        return $files;
    }

    /**
     * Overriden - Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {
        return shell_exec($this->getSystemPath() . " -c " . $files['ini'] . " " . $files['php']);
    }
}
