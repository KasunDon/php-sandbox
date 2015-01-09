<?php

/**
 * Document Class for Mongo-Code Mondel
 */
class Code extends MongoModel {

    /**
     * Class Constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PRIVATE = 'private';
    const VIEW_LINK = 'http://phpbox.info/share/';

    /**
     * Document required parameters
     * 
     * @var array 
     */
    public static $REQUIRED_PARMS = array(
        'code', 'output', 'version', 'create_time'
    );

    /**
     * Returns a preapared Document
     * 
     * @param array $params
     * @return array
     */
    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            'expiry' => null,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'theme' => 'xcode',
            'status' => self::STATUS_ACTIVE,
            '_id' => new MongoId()
        ));
    }

    /**
     * Reurns prepared document via static
     * 
     * @return array
     */
    public static function doc() {
        $self = new self();
        return $self->getDocument(self::$REQUIRED_PARMS);
    }

}
