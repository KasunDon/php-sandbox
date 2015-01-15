<?php

namespace App\Models;

/**
 * Abstract Class for Mongo Models
 */
abstract class MongoModel {

    /**
     * Returns preapred document
     * 
     * @param array $params
     * @return array
     */
    public function getDocument(array $params) {
        $document = array();

        foreach ($params as $value) {
            if ($value === "code") {
                $document[$value] = htmlentities(\Input::get($value));
                continue;
            }
            $document[$value] = \Input::get($value);
        }

        return $document;
    }

}
