<?php

class Shell extends App\Models\Sandbox{
    
    /**
     * Constructor
     * 
     * @param string $sourceCode
     * @param string $version
     */
    public function __construct($type, $version) {
        parent::__construct($version, null);
        $this->setType($type);
        $this->setServers(\App::make('app.config.env')->VIRTSTORE);
    }
    
     /**
     * Overriden - Executed Shell Commands
     * 
     * @param type $files
     */
    protected function _cmd($files) {
        
        $address = App\Models\IpResolver::get(App\Models\IpResolver::LOCAL_ADDR);
        $port = App\Models\IpResolver::getPort();
        $hostname = $this->createDynamicHostName();
        
        //add proxy host
        $this->_addRoutable($hostname, $address . ":" . $port);
        
       $container = shell_exec("docker run -it -p $port:15000 -d --env-file /etc/sandbox/env.list "
               . "kasundon/phpbox_term:core /usr/local/bin/node /src/index.js");
                
       return "<a href='http://" . $hostname . "/static/term.html'>" . $hostname . "- ($container) </a>";
    }
    
    /**
     *  Returns router.json path
     * 
     * @return string
     */
    protected function getRouteTablePath() {
        return app_path() . '/utils/router.json';
    }
    
    /**
     * Adding routable to node proxy table
     * 
     * @param array $routable
     */
    protected function _addRoutable($hostname, $address){
        $router = (! file_exists($this->getRouteTablePath()))?
                array(): json_decode(file_get_contents($this->getRouteTablePath()), true);
    
        $router[$hostname] = $address;
        
        file_put_contents($this->getRouteTablePath(), json_encode($router));
    }

    /**
     * Overriding - Returns resource address
     * 
     * @param string $route
     * @return string
     */
    protected function _getAddress($route) {
        return "http://beta.yard.phpbox.info/service/terminal";
    }

    /**
     * Creates Dynamic hostname
     */
    protected function createDynamicHostName() {
        return substr(md5($this->getType() + microtime()), 0, 6) . 
                "-" . substr(md5(time()), 0, 4) . "-" . 
                substr(md5(rand(1, 1000)), 0, 5) . '.service.phpbox.info';
    }
    
    /**
     * Overriden - Prepares payload
     * 
     * @return array
     */
    protected function _getPayload() {
        return array('type' => $this->getType(), 'version' => $this->getVersion());
    }

    /**
     * Overriden - Settings for sandbox
     * 
     * @param array $files
     * @throws \App\Exception\FileCopyException
     */
    protected function _sandboxSettings($files) {
        return $files;
    }
}
