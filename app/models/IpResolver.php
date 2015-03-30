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
     * @param array $servers
     * @return mixed
     */
    protected function findRoute($servers) {
        $localAddressList = self::getServers($servers);

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
     * @param array $servers
     * @return mixed
     */
    public static function route($servers) {
        $self = new self;
        return $self->findRoute($servers);
    }
    
    /**
     * List all local servers
     * 
     * @param array $servers
     * @return array
     */
    public static function getServers($servers) {
        $servers = Utils::parseJson($servers, true, true);
        return array_keys($servers);
    }
}
