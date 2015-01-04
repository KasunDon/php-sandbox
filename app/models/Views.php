<?php

class Views extends MongoModel {

    public static $REQUIRED_PARMS = array(
    );
    private $_id;

    public function getId() {
        return $this->_id;
    }

    public function setId($_id) {
        $this->_id = $_id;
    }

    public function getDocument(array $params) {
        return array_merge(parent::getDocument($params), array(
            '_id' => new MongoId($this->getId()),
            'views' => 0
        ));
    }

    public static function doc($id) {
        $self = new self();
        $self->setId($id);
        return $self->getDocument(self::$REQUIRED_PARMS);
    }

}
