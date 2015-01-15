<?php
namespace App\Models;

/**
 * Document Class for Mongo-Views Mondel
 */
class Views extends MongoModel {

    /**
     * Document required parameters
     * 
     * @var array 
     */
    public static $REQUIRED_PARMS = array();

    /**
     * Id
     * 
     * @var string/ObjectId 
     */
    private $_id;

    /**
     * Returns Id
     * 
     * @return string/ObjectId
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Set Id
     * 
     * @param string $_id
     */
    public function setId($_id) {
        $this->_id = $_id;
    }

    /**
     * Returns a preapared Document
     * 
     * @param array $params
     * @return array
     */
    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            '_id' => new \MongoId($this->getId()),
            'views' => 0
        ));
    }

    /**
     * Reurns prepared document with given id
     * 
     * @param string $id
     * @return array
     */
    public static function doc($id) {
        $self = new self();
        $self->setId($id);
        return $self->getDocument(self::$REQUIRED_PARMS);
    }

}
