<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IpResolver {

    /**
     * Class Constants
     */
    const LOCAL_ADDR = 'eth1';
    const REMOTE_ADDR = 'eth0';

    /**
     * Return Address from shell
     * 
     * @param string $interface
     * @param boolean $range
     * @return string
     */
    protected function getAddress($interface, $range = false) {
        $output = shell_exec("ip addr show dev $interface | grep \"inet \" | awk '{ print $2 }'");
        return ($range) ? $output : current(explode('/', $output));
    }

    /**
     * Finds a route
     * 
     * @return mixed
     */
    protected function findRoute() {
        $servers = Utils::parseJson(\App::make('app.config.env')->PHP_SANDBOX_SERVERS, true, true);
        $localAddressList = array_keys($servers);

        if (in_array(self::get(self::LOCAL_ADDR), $localAddressList)) {
            return false;
        }

        return $localAddressList[array_rand($localAddressList)];
    }

    /**
     * Statically Get Ip address 
     * 
     * @param string $interface
     * @param boolean $range
     * @return string
     */
    public static function get($interface, $range = false) {
        $self = new self;
        return $self->getAddress($interface, $range);
    }

    /**
     * Find dynamic route to specified server
     * 
     * @return mixed
     */
    public static function route() {
        $self = new self;
        return $self->findRoute();
    }

}
