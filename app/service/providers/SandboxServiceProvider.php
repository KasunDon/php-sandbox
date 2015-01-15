<?php

namespace App\Service\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Config\Config;

/*
 * Sandbox Service Provider - Registering all required models as service
 * 
 */

class SandboxServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register() {
        $this->app->singleton('app.config.env', function($app) {
            return Config::pull(Config::ENVIRONMENT_VARS);
        });
    }

}
