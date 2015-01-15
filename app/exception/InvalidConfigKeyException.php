<?php

namespace App\Exception;

/*
 * Invalid Config Key failure Exception
 */

class InvalidConfigKeyException extends \Exception {

    /**
     * Exception message
     * 
     * @var string 
     */
    protected $message = 'Invalid config key has been requested.';

}
