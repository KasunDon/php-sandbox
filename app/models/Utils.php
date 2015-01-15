<?php
namespace App\Models;

/* 
 * Utility class
 */
class Utils {
    
    /**
     * Parse Json
     * 
     * @param string $content
     * @param boolean $assoc
     * @param boolean $path
     * @return mixed
     */
    public static function parseJson($content, $assoc = false, $isPath = false){
        return json_decode((($isPath && ! empty($content))? file_get_contents($content): $content), $assoc);
    }
    
}

