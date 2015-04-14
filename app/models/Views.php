<?php
namespace App\Models;

/**
 * Document Class for Mongo-Views Mondel
 */
class Views extends MongoModel {
    
    /**
     * Tracking code length
     */
    const TRACKING_CODE_LENGTH = 5;
    
    /**
     * Tracking code chars
     */
    const TRACKING_CODE_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

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
            'views' => 0,
            'tracking_code' => $this->getTrackingCode()
        ));
    }
    
    /**
     * Get Unique String
     * 
     * @return string
     */
    public function getTrackingCode() {
        while (true) {
            $random = substr(str_shuffle(self::TRACKING_CODE_CHARS), 0, self::TRACKING_CODE_LENGTH);
            
            $objectId = self::objectIdByTrackingCode($random);
            
            if (empty($objectId)) {
                return $random;
            }
        }
    }
    
    /**
     * Object Id by tracking code
     * 
     * @param string $code
     * @return mixed
     */
    public static function objectIdByTrackingCode($code) {
        return Storage::instance('views')->getCollection()
                    ->findOne(array('tracking_code' => $code));
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
