<?php

class Feedback  extends MongoModel{

    const STATUS_NEW = 'new';
    const STATUS_VIEWED = 'viewed';

    public static $REQUIRED_PARMS = array(
        'feedback'
    );

    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            'date_time' => new DateTime(),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'status' => self::STATUS_NEW
        ));
    }

    public static function doc() {
        $self = new self();
        return $self->getDocument(self::$REQUIRED_PARMS);
    }

}
