<?php

namespace App\Models;

use App\Models\Utils;

/**
 * Class for PHP Sandbox runtime access
 */
class PHPSandBox {
    
    /**
     * Available PHP runtime versions
     * 
     * @var array 
     */
    public static $VERSIONS;

    /**
     * Version
     * 
     * @var string 
     */
    private $version;

    /**
     * System path
     * 
     * @var string 
     */
    private $systemPath;

    /**
     * Source Code
     * 
     * @var string 
     */
    private $sourceCode;
    
    /**
     * Servers
     * 
     * @var array 
     */
    private $servers;
    
    /**
     * Constructor
     * 
     * @param string $version
     * @param string $sourceCode
     */
    public function __construct($version, $sourceCode) {
        $this->setVersion($version);
        $this->setSourceCode($sourceCode);
        $this->validate();
        $this->setServers(\App::make('app.config.env')->PHP_SANDBOX_SERVERS);
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
    protected function _getAddress($route) {
        $version = $this->getVersion();
        return "https://$route/api/php/$version/run";
    }

    /**
     * Prepares payload
     * 
     * @return array
     */
    protected function _getPayload() {
        return array('v' => $this->getVersion(), 'code' => $this->getSourceCode());
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
     * Removing all files
     * 
     * @param array $files
     */
    protected function _clear($files) {
        //force delete sandbox folder
        shell_exec("rm -rf {$files['sandbox']}");
    }

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
     * Settings for sandbox
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
     * Returns output
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
     * Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {
        return shell_exec($this->getSystemPath() . " -c " . $files['ini'] . " " . $files['php']);
    }

    /**
     * Returns Version
     * 
     * @return type
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Returns System path
     * @return type
     */
    public function getSystemPath() {
        return $this->systemPath;
    }

    /**
     * Return Source Code
     * 
     * @return type
     */
    public function getSourceCode() {
        return $this->sourceCode;
    }

    /**
     * Set Version
     * 
     * @param type $version
     */
    public function setVersion($version) {
        $this->version = $version;
    }

    /**
     *  Set System path
     * 
     * @param type $systemPath
     */
    public function setSystemPath($systemPath) {
        $this->systemPath = $systemPath;
    }

    /**
     * Set Source Code 
     * 
     * @param type $sourceCode
     */
    public function setSourceCode($sourceCode) {
        $this->sourceCode = $sourceCode;
    }
    
    /**
     * Return servers
     * 
     * @return array
     */
    public function getServers() {
        return $this->servers;
    }

    /**
     * Setting servers
     * 
     * @param array $servers
     */
    public function setServers($servers) {
        $this->servers = $servers;
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
     * Returns list of versions
     * 
     * @return array
     */
    public static function versions() {
        if(empty(self::$VERSIONS)) {
            self::$VERSIONS = \App\Models\Utils::parseJson(\App::make('app.config.env')->PHP_SANDBOX_VERSIONS, true, true);
        }

        return array_keys(self::$VERSIONS);
    }
}
