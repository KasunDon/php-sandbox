<?php

abstract class MongoModel {

    public function getDocument(array $params) {
        $document = array();

        foreach ($params as $value) {
            if ($value === "code") {
                $document[$value] = htmlentities(Input::get($value));
                continue;
            }
            $document[$value] = Input::get($value);
        }

        return $document;
    }

}
