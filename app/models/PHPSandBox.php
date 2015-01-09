<?php

/**
 * Class for PHP Sandbox runtime access
 */
class PHPSandBox {

    /**
     * Constant declaration for INI file
     */
    const INI_FILE = "/var/www/html/sandbox/sanbox-php.ini";

    /**
     * Available PHP runtime versions
     * 
     * @var array 
     */
    public static $VERSIONS = array(
        '4.4.0' => '/opt/phpfarm/inst/bin/php-4.4.0',
        '4.4.1' => '/opt/phpfarm/inst/bin/php-4.4.1',
        '4.4.3' => '/opt/phpfarm/inst/bin/php-4.4.3',
        '4.4.5' => '/opt/phpfarm/inst/bin/php-4.4.5',
        '4.4.9' => '/opt/phpfarm/inst/bin/php-4.4.9',
        '5.0.5' => '/opt/phpfarm/inst/bin/php-5.0.5',
        '5.1.0' => '/opt/phpfarm/inst/bin/php-5.1.0',
        '5.1.5' => '/opt/phpfarm/inst/bin/php-5.1.5',
        '5.1.6' => '/opt/phpfarm/inst/bin/php-5.1.6',
        '5.2.0' => '/opt/phpfarm/inst/bin/php-5.2.0',
        '5.2.3' => '/opt/phpfarm/inst/bin/php-5.2.3',
        '5.2.5' => '/opt/phpfarm/inst/bin/php-5.2.5',
        '5.2.8' => '/opt/phpfarm/inst/bin/php-5.2.8',
        '5.3.0' => '/opt/phpfarm/inst/bin/php-5.3.0',
        '5.3.1' => '/opt/phpfarm/inst/bin/php-5.3.1',
        '5.3.2' => '/opt/phpfarm/inst/bin/php-5.3.2',
        '5.3.3' => '/opt/phpfarm/inst/bin/php-5.3.3',
        '5.3.5' => '/opt/phpfarm/inst/bin/php-5.3.5',
        '5.3.8' => '/opt/phpfarm/inst/bin/php-5.3.8',
        '5.3.10' => '/opt/phpfarm/inst/bin/php-5.3.10',
        '5.3.11' => '/opt/phpfarm/inst/bin/php-5.3.11',
        '5.3.12' => '/opt/phpfarm/inst/bin/php-5.3.12',
        '5.3.13' => '/opt/phpfarm/inst/bin/php-5.3.13',
        '5.3.14' => '/opt/phpfarm/inst/bin/php-5.3.14',
        '5.3.15' => '/opt/phpfarm/inst/bin/php-5.3.15',
        '5.3.16' => '/opt/phpfarm/inst/bin/php-5.3.16',
        '5.3.17' => '/opt/phpfarm/inst/bin/php-5.3.17',
        '5.3.18' => '/opt/phpfarm/inst/bin/php-5.3.18',
        '5.3.19' => '/opt/phpfarm/inst/bin/php-5.3.19',
        '5.3.20' => '/opt/phpfarm/inst/bin/php-5.3.20',
        '5.3.21' => '/opt/phpfarm/inst/bin/php-5.3.21',
        '5.3.22' => '/opt/phpfarm/inst/bin/php-5.3.22',
        '5.4.0' => '/opt/phpfarm/inst/bin/php-5.4.0',
        '5.4.13' => '/opt/phpfarm/inst/bin/php-5.4.13',
        '5.5.1' => '/opt/phpfarm/inst/bin/php-5.5.1',
        '5.5.6' => '/opt/phpfarm/inst/bin/php-5.5.6',
        '5.6.2' => '/opt/phpfarm/inst/bin/php-5.6.2'
    );

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
        $checksum = sha1($this->getSourceCode() . $this->getVersion() . time());

        //create directory
        $tmpFolder = '/data/temp/' . $checksum;

        mkdir($tmpFolder);

        $file = $tmpFolder . "/" . $checksum . ".php";

        $tempIni = $tmpFolder . "/" . $checksum . ".ini";

        //copy default php.ini to temp location
        if (!copy("/opt/phpfarm/inst/php-" . $this->getVersion() . "/lib/php.ini", $tempIni)) {
            throw new Exception("Couldn't copy ini file");
        }

        $customIniContent = "\n" . file_get_contents(self::INI_FILE) . "\n";
        $customIniContent .= 'open_basedir = "' . $tmpFolder . '"' . "\n";

        //adding custom ini settings to temp ini file
        file_put_contents($tempIni, $customIniContent, FILE_APPEND);

        file_put_contents($file, str_replace("\r\n\r\n\r\n", "", $this->getSourceCode()));

        $output = shell_exec($this->getSystemPath() . " -c " . $tempIni . " " . $file);
        $output = str_replace($file, "SandBox-Request", $output);
        $output = str_replace($tmpFolder, "SandBox-Request", $output);

        //clearing up signatures
        $output = str_replace(array("Content-type: text/html", "X-Powered-By: PHP/" . $this->getVersion(), "\r\n\r\n\r\n"), "", $output);

        if (preg_match("/^\s*$/", $output)) {
            $output = "No output!";
        }

        unlink($file);
        unlink($tempIni);
        rmdir($tmpFolder);

        return $output;
    }

    /**
     * Validate settings
     * 
     * @throws Exception
     */
    public function validate() {
        if (!$this->isVersion($this->getVersion())) {
            throw new Exception('Requested version not avaialble :: ' . $this->getVersion());
        }

        if (!preg_match('/^[<][?]php/', $this->getSourceCode())) {
            $this->setSourceCode("<?php " . $this->getSourceCode());
        }

        $this->setSystemPath(self::$VERSIONS[$this->getVersion()]);
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

}
