<?php

namespace App\Models;

use App\Models\Utils;

/**
 * Core sandbox functionality
 */
abstract class Sandbox {

    /**
     * Available runtime versions
     * 
     * @var array 
     */
    public static $VERSIONS = array();
    
    /**
     *
     * @var array 
     */
    protected static $VERSION_VARS = array(
        'PHP_SANDBOX_VERSIONS', 'SANDBOX_HHVM_VERSIONS'
    );

    /**
     * Version
     * 
     * @var string 
     */
    protected $_version;

    /**
     * System path
     * 
     * @var string 
     */
    protected $_systemPath;

    /**
     * Source Code
     * 
     * @var string 
     */
    protected $_sourceCode;
    
    /**
     * Servers
     * 
     * @var array 
     */
    protected $_servers;
    
    /**
     * Constructor
     * 
     * @param string $sourceCode
     * @param string $version
     */
    public function __construct($version, $sourceCode) {
        $this->setSourceCode($sourceCode);
        $this->setVersion($version);
        $this->validate();
    }
    
    /**
     * Returns list of versions
     * 
     * @return array
     */
    public static function versions() {
        if (empty(self::$VERSIONS)) {
            foreach (self::$VERSION_VARS as $var) {
                array_push(self::$VERSIONS, \App\Models\Utils::parseJson(\App::make('app.config.env')->$var, true, true));
            }
        }

        return array_keys(self::$VERSIONS);
    }
    
    /**
     * Checks whether given runtime version available
     * 
     * @param string $version
     * @return boolean
     */
    public function isVersion($version) {
        return (in_array($version, array_keys(self::$VERSIONS))) ? true : false;
    }
    
    /**
     * Returns resource address
     * 
     * @param string $route
     * @return string
     */
    protected abstract function _getAddress($route);
    
    /**
     * Prepares payload
     * 
     * @return array
     */
    protected abstract function _getPayload();
    
    /**
     * Settings for sandbox
     * 
     * @param array $files
     * @throws \App\Exception\FileCopyException
     */
    protected abstract function _sandboxSettings($files);
    
    /**
     * Executed Shell Commands
     * 
     * @param type $files
     */
    protected abstract function _cmd($files);
    
    /**
     * Preparing sandbox
     * 
     * @return string
     * @throws \App\Exception\FileCopyException
     */
    protected function _prepareSandbox() {
        $checksum = sha1($this->getSourceCode() . $this->getVersion() . time());

        $files = array('sandbox' => \App::make('app.config.env')->SANDBOX . $checksum);

        $files['php'] = $files['sandbox'] . "/" . $checksum . ".php";
        $files['ini'] = $files['sandbox'] . "/" . $checksum . ".ini";

        // create sandbox path
        mkdir($files['sandbox']);

        //replacing empty spaces
        file_put_contents($files['php'], str_replace("\r\n\r\n\r\n", "", $this->getSourceCode()));
        
        //change directory
        chdir($files['sandbox']);
        
        return $this->_sandboxSettings($files);
    }
    
    /**
     * Executes settings on runtime
     * 
     * @return string
     * @throws Exception
     */
    public function execute() {

        $route = IpResolver::route($this->getServers());
        
        if($route){
            return $this->remote($this->_getAddress($route), $this->_getPayload());
        }

        $files = $this->_prepareSandbox();

        $output = $this->_getOutput($files);

        $this->_clear($files);

        return $output;
    }
    
    /**
     * Validate settings
     * 
     * @throws Exception
     */
    public function validate() {
        if (!$this->isVersion($this->getVersion())) {
            throw new \Exception('Requested version not avaialble :: ' . $this->getVersion());
        }
        
        $code = $this->getSourceCode();
        
        $code = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $code);
        
        if (! preg_match('/^[<][?]php/i', $code) && ! preg_match('/^[<][?]/', $code)) {
            $this->setSourceCode("<?php " . $this->getSourceCode());
        }

        $this->setSystemPath(self::$VERSIONS[$this->getVersion()]);
    }
    
    /**
     * Validates output and returns it
     * 
     * @param array $files
     * @return string
     */
    protected function _getOutput($files) {

        $output = $this->_cmd($files);

        foreach ($files as $file) {
            $output = str_replace($file, "SandBox-Request", $output);
        }

        //clearing up signatures
        $output = str_replace(array("Content-type: text/html", "X-Powered-By: PHP/" .
            $this->getVersion(), "\r\n\r\n\r\n"), "", $output);

        if (preg_match("/^\s*$/", $output)) {
            $output = "No output!";
        }
        return $output;
    }
    
    /**
     * Execute code on remote server
     * 
     * @param string $address
     * @param string $source
     * @param string $version
     * @return string
     */
    private function remote($address, $params, $method = Utils::CURL_POST) {
        $output = Utils::curl($address, $params, $method);
        $json = json_decode($output, true);
        return $json['output'];
    }
    
    /**
     * Removing all files
     * 
     * @param array $files
     */
    protected function _clear($files) {
        //force delete sandbox folder
        shell_exec("rm -rf {$files['sandbox']}");
    }
    
    /**
     * Returns Version
     * 
     * @return type
     */
    public function getVersion() {
        return $this->_version;
    }

    /**
     * Returns System path
     * @return type
     */
    public function getSystemPath() {
        return $this->_systemPath;
    }

    /**
     * Return Source Code
     * 
     * @return type
     */
    public function getSourceCode() {
        return $this->_sourceCode;
    }

    /**
     * Set Version
     * 
     * @param type $version
     */
    public function setVersion($version) {
        $this->_version = $version;
    }

    /**
     *  Set System path
     * 
     * @param type $systemPath
     */
    public function setSystemPath($systemPath) {
        $this->_systemPath = $systemPath;
    }

    /**
     * Set Source Code 
     * 
     * @param type $sourceCode
     */
    public function setSourceCode($sourceCode) {
        $this->_sourceCode = $sourceCode;
    }
    
    /**
     * Return servers
     * 
     * @return array
     */
    public function getServers() {
        return $this->_servers;
    }

    /**
     * Setting servers
     * 
     * @param array $servers
     */
    public function setServers($servers) {
        $this->_servers = $servers;
    }

}
