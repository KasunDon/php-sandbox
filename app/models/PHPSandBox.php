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
    private $version = null;

    /**
     * System path
     * 
     * @var string 
     */
    private $systemPath = null;

    /**
     * Source Code
     * 
     * @var string 
     */
    private $sourceCode = null;

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
     * Executes settings on runtime
     * 
     * @return string
     * @throws Exception
     */
    public function execute() {
        
        if (\App::make('app.config.env')->APP_ENV !== 'local') {
            $route = IpResolver::route();
        
            if($route){
                return $this->remote($route, $this->getSourceCode(), $this->getVersion());
            }
        }
        
        $checksum = sha1($this->getSourceCode() . $this->getVersion() . time());

        $files = $this->prepareSandbox($checksum);

        $output = $this->getOutput($files);

        $this->clear($files);

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

        if (!preg_match('/^[<][?]php/', $this->getSourceCode())) {
            $this->setSourceCode("<?php " . $this->getSourceCode());
        }

        $this->setSystemPath(self::$VERSIONS[$this->getVersion()]);
    }

    /**
     * Removing all files
     * 
     * @param array $files
     */
    private function clear($files) {
        //force delete sandbox folder
        shell_exec("rm -rf {$files['sandbox']}");
    }

    /**
     * Preparing sandbox
     * 
     * @param array $checksum
     * @return string
     * @throws \App\Exception\FileCopyException
     */
    private function prepareSandbox($checksum) {

        $files = array('sandbox' => \App::make('app.config.env')->SANDBOX . $checksum);

        $files['php'] = $files['sandbox'] . "/" . $checksum . ".php";
        $files['ini'] = $files['sandbox'] . "/" . $checksum . ".ini";

        // create sandbox path
        mkdir($files['sandbox']);

        //copy default php.ini to sandbox
        if (!copy(sprintf(\App::make('app.config.env')->PHP_SANDBOX_PATH, $this->getVersion()), $files['ini'])) {
            throw new \App\Exception\FileCopyException();
        }

        $ini_settings = "\n" . file_get_contents(\App::make('app.config.env')->INI_FILE) . "\n";
        $ini_settings .= 'open_basedir = "' . $files['sandbox'] . '"' . "\n";

        //adding custom ini settings to temp ini file
        file_put_contents($files['ini'], $ini_settings, FILE_APPEND);

        //replacing empty spaces
        file_put_contents($files['php'], str_replace("\r\n\r\n\r\n", "", $this->getSourceCode()));
        
        //change directory
        chdir($files['sandbox']);

        return $files;
    }

    /**
     * Returns output
     * 
     * @param array $files
     * @return string
     */
    private function getOutput($files) {

        $output = shell_exec($this->getSystemPath() . " -c " . $files['ini'] . " " . $files['php']);

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
     * Execute code on remote server
     * 
     * @param string $address
     * @param string $source
     * @param string $version
     * @return string
     */
    private function remote($address, $source, $version) {
        $output = Utils::curl("https://$address/api/php/$version/run", array('v' => $version, 'code' => $source), Utils::CURL_POST);
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
