<?php

namespace App\Models;

/**
 * Document Class for Mongo-Feedback Mondel
 */
class Feedback extends MongoModel {

    /**
     * Class Constants
     */
    const STATUS_NEW = 'new';
    const STATUS_VIEWED = 'viewed';

    /**
     * Document required parameters
     * 
     * @var array 
     */
    public static $REQUIRED_PARMS = array(
        'feedback'
    );

    /**
     * Returns a preapared Document
     * 
     * @param array $params
     * @return array
     */
    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            'date_time' => new DateTime(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'status' => self::STATUS_NEW
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
