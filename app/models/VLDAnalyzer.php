<?php

namespace App\Models;

/**
 * Class for handle Vulcan Logic Disassembler (VLD)
 */
class VLDAnalyzer extends PHPSandBox {

    /**
     * Constructor
     * 
     * @param string $sourceCode
     * @param string $version
     */
    public function __construct($sourceCode, $version = "5.5.6") {
        parent::__construct($version, $sourceCode);
        $this->setSystemPath(parent::PHP_HOST);
    }

    /**
     *  Returns Disassembled code logic
     * 
     * @return string
     */
    public function disassemble() {
        $files = $this->_prepareSandbox();

        $output = $this->_getOutput($files);

        $this->_clear($files);

        return $output;
    }

    /**
     * Overriden - Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {
        $process = proc_open($this->getSystemPath() . " -c " . $files['ini'] .
                " -dextension=vld.so -dvld.active=1 -dvld.verbosity=1 -dvld.execute=0 " . $files['php'], array(2 => array("pipe", "w")), $pipes);

        if (is_resource($process)) {
            $output = stream_get_contents($pipes[2], 1000000);
            fclose($pipes[2]);

            $value = proc_close($process);
        }
        return $output . shell_exec($this->getSystemPath() . " -c " . $files['ini'] .
                " -dextension=vld.so -dvld.active=1 -dvld.verbosity=0 -dvld.execute=0 " . $files['php']);
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
