<?php

namespace App\Exception;

/*
 * Invalid Config Key failure Exception
 */

class FileCopyException extends \Exception {

    /**
     * Exception message
     * 
     * @var string 
     */
    protected $message = "Couldn't Copy requested file.";

}
