<?php

namespace App\Models;

use App\Models\Utils;

class SandboxClient {

    private static $endpoint = "http://sandbox.phpbox.info/api/";

    public static function request($api, $version, $code) {
        return json_decode(Utils::curl(self::$endpoint . "$api/$version", array('code'=> base64_encode($code)), Utils::CURL_POST), true);
    }
    
    public static function versions() {
         return json_decode(Utils::curl(self::$endpoint . "versions"), true);
    }
    
    public static function getPHPSyntaxRefs($version, $code) {
        return json_decode(Utils::curl(self::$endpoint . "php/syntaxer",
                array('code'=> base64_encode($code), 'version' => $version), Utils::CURL_POST), true);
    }

}
