<?php

namespace App\Models;

use App\Models\Utils;
use Carbon\Carbon;

class SandboxClient {

    public static function request($api, $version, $code) {
        return json_decode(Utils::curl(SANDBOX_API_ENDPOINT . "$api/$version", array('code' => base64_encode($code)), Utils::CURL_POST), true);
    }

    public static function versions() {
        $cacheKey = "api-versions";

        if (\Cache::has($cacheKey) && !empty($versions = \Cache::get($cacheKey))) {
            return $versions;
        }

        $versions = json_decode(Utils::curl(SANDBOX_API_ENDPOINT . "versions"), true);

        \Cache::add($cacheKey, $versions, Carbon::now()->addMinutes(60));

        return $versions;
    }

    public static function getPHPSyntaxRefs($version, $code) {
        return json_decode(Utils::curl(SANDBOX_API_ENDPOINT . "php/syntaxer", array('code' => base64_encode($code), 'version' => $version), Utils::CURL_POST), true);
    }

}
