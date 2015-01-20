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

        $this->app->singleton('app.auth.service', function($app) {
            $redisClient = new \Predis\Client('tcp://10.131.211.185:6379');
            $storage = new \OAuth2\Storage\Redis($redisClient);
            $server = new \OAuth2\Server($storage);
            $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
            $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));

            $defaultScope = 'basic';
            
            $supportedScopes = array(
                'basic',
                'postonwall',
                'accessphonenumber'
            );
            
            $memory = new \OAuth2\Storage\Memory(array(
                'default_scope' => $defaultScope,
                'supported_scopes' => $supportedScopes
            ));
            
            $scopeUtil = new \OAuth2\Scope($memory);

            $server->setScopeUtil($scopeUtil);
            
            return $server;
        });

        $this->app->singleton('app.auth.storage', function($app) {
            $redisClient = new \Predis\Client('tcp://10.131.211.185:6379');
            $storage = new \OAuth2\Storage\Redis($redisClient);
            return $storage;
        });
    }

}
