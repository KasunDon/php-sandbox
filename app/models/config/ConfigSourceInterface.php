<?php

namespace App\Models\Config;

/**
 * Config Source Mehtod Interface
 */
interface ConfigSourceInterface {

    /**
     * Loading Configuration
     */
    public function load();
}
