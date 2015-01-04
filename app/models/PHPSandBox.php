<?php

class PHPSandBox {

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
        '5.5.0' => '/opt/phpfarm/inst/bin/php-5.5.0',
        '5.5.1' => '/opt/phpfarm/inst/bin/php-5.5.1',
        '5.5.6' => '/opt/phpfarm/inst/bin/php-5.5.6',
        '5.6.2' => '/opt/phpfarm/inst/bin/php-5.6.2'
    );
    private $version = null;
    private $systemPath = null;
    private $sourceCode = null;

    public function __construct($version, $sourceCode) {
        $this->setVersion($version);
        $this->setSourceCode($sourceCode);
        $this->validate();
    }

    public function isVersion($version) {
        return (in_array($version, array_keys(self::$VERSIONS))) ? true : false;
    }

    public function execute() {
        $checksum  = sha1($this->getSourceCode() . $this->getVersion() . time());
        
        //create directory
        $tmpFolder = '/data/temp/' . $checksum;
        
        mkdir($tmpFolder);
        
        $file = $tmpFolder . "/" . $checksum . ".php";

        //add ini settings
        $iniSettings = " ini_set('open_basedir', '$tmpFolder');";
        $this->setSourceCode(substr_replace($this->getSourceCode(), $iniSettings, strpos($this->getSourceCode(), "<?php") + 5, 0));

        file_put_contents($file, str_replace("\r\n\r\n\r\n", "", $this->getSourceCode()));

        $output = shell_exec($this->getSystemPath() . " " . $file);

        $output = str_replace($file, "SandBox-Request", $output);
        
        //clearing up signatures
        $output = str_replace(array("Content-type: text/html", "X-Powered-By: PHP/" . $this->getVersion(), "\r\n\r\n\r\n"), "", $output);

        if (preg_match("/^\s*$/", $output)) {
            $output = "No output!";
        }

        unlink($file);
        rmdir($tmpFolder);

        return $output;
    }

    public function validate() {
        if (!$this->isVersion($this->getVersion())) {
            throw new Exception('Requested version not avaialble :: ' . $this->getVersion());
        }

        if (!preg_match('/^[<][?]php/', $this->getSourceCode())) {
            $this->setSourceCode("<?php " . $this->getSourceCode());
        }

        $this->setSystemPath(self::$VERSIONS[$this->getVersion()]);
    }

    public function getVersion() {
        return $this->version;
    }

    public function getSystemPath() {
        return $this->systemPath;
    }

    public function getSourceCode() {
        return $this->sourceCode;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function setSystemPath($systemPath) {
        $this->systemPath = $systemPath;
    }

    public function setSourceCode($sourceCode) {
        $this->sourceCode = $sourceCode;
    }

}
