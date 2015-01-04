<?php

abstract class AbstractSandBox {

    public static $VERSIONS = array(
        'PHP' => array(
            '4.4.9',
            '5.5.6'
        )
    );
    protected $_language = null;
    protected $_currentVersion = null;

    public function getLanguage() {
        return $this->_language;
    }

    public function getCurrentVersion() {
        return $this->_currentVersion;
    }

    public function setLanguage($language) {
        $this->_language = $language;
    }

    public function setCurrentVersion($currentVersion) {
        $this->_currentVersion = $currentVersion;
    }
    
}
