<?php

namespace App\Models;

/*
 * Utility class
 */

class Utils {

    /**
     * Class Constants
     */
    const CURL_POST = 'POST';
    const CURL_GET = 'GET';

    /**
     * Parse Json
     * 
     * @param string $content
     * @param boolean $assoc
     * @param boolean $path
     * @return mixed
     */
    public static function parseJson($content, $assoc = false, $isPath = false) {
        return json_decode((($isPath && !empty($content)) ? file_get_contents($content) : $content), $assoc);
    }

    /**
     * Curl Client
     * 
     * @param string $url
     * @param array $params
     * @param string $method
     * @return type
     * @throws \App\Exception\CommunicationException
     */
    public static function curl($url, array $params = null, $method = self::CURL_GET) {
        $ch = curl_init();

        //CURL Settings
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        if ($method == self::CURL_POST) {
            $postParams = '';
            foreach ($params as $key => $value) {
                $postParams .= $key . "=" . $value . "&";
            }
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        }

        $output = curl_exec($ch);

        if ($output === false) {
            throw new \App\Exception\CommunicationException(curl_error($ch));
        }

        curl_close($ch);

        return $output;
    }

}
