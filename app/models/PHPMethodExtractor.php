<?php

namespace App\Models;

/**
 * Class for handle source code keyword analyzer
 */
class PHPMethodExtractor {
    /**
     * PHP.net localtion
     */
    const PHP_NET = "https://php.net/manual/en/";
    
    /**
     * Functions
     * 
     * @var array 
     */
    protected $_functions;
    
    /**
     * Sources
     * 
     * @var string 
     */
    protected $_source;
    
    /**
     * Version
     * 
     * @var string 
     */
    protected $_version;

    /**
     * Setting list of functions
     * 
     * @param array $functions
     * @return \App\Models\PHPMethodExtractor
     */
    public function setFunctions($functions) {
        $this->_functions = $functions;
        return $this;
    }

    /**
     * Setting version  
     * 
     * @param string $version
     * @return \App\Models\PHPMethodExtractor
     */
    public function setVersion($version) {
        $this->_version = $version;
        return $this;
    }

    /**
     * Setting source
     * 
     * @param string $source
     * @return \App\Models\PHPMethodExtractor
     */
    public function setSource($source) {
        $this->_source = $source;
        return $this;
    }

    /**
     * Tokenize given string
     * 
     * @param string $string
     * @return array
     */
    private function tokenize($string) {
        return token_get_all($string);
    }

    /**
     * Gets list of internal function by given php version
     */
    protected function _fetchDefaultFunctions() {
        $box = new PHPAPI($this->_version, "<?php echo serialize(get_defined_functions()); ?>");
        
        $methods = unserialize($box->execute());
        
        sort($methods['internal']);
        
        $this->setFunctions($methods['internal']);
        
        return $methods['internal'];
    }

    /**
     * Get PHP code references
     * 
     * @return array
     */
    public function getReferences() {
        $tokens = $this->tokenize($this->_source);
        
        //if functions not set fetch based on php version
        if (empty($this->_functions)) {
            $this->_fetchDefaultFunctions();
        }

        $raw = array();

        foreach ($tokens as $token) {
            if (!is_string($token)) {
                list($tokenId, $value) = $token;
                switch ($tokenId) {
                    case T_STRING:
                        $raw[] = $value;
                        break;

                    default:
                        break;
                }
            }
        }

        //finding unique values
        $raw = array_unique($raw);

        $nonInternal = array_diff($raw, $this->_functions);
        
        //declared php core functions
        $methods = array_flip(array_diff($raw, $nonInternal));

        //appending php.net url
        foreach ($methods as $name => &$url) {
            $url = self::PHP_NET . "function." . str_replace(array("_"), array("-"), $name) . ".php";
        }

        return $methods;
    }
              
    /**
     * Allowing statically access self instance
     * 
     * @return \self
     */
    public static function init() {
        return new self();
    }
}
