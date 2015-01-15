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
    const LOCAL_ADDR = 'local';
    const REMOTE_ADDR = 'remote';

    /**
     * Network Interface Mapping
     * @var type 
     */
    protected $_interfaceMapping = array(
        self::LOCAL_ADDR => 'eth0',
        self::REMOTE_ADDR => 'eth1'
    );
    
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

}
