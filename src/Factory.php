<?php
    /**
     * LxdClient - LXC PHP Client
     * 
     * (3rd Party Software)
     * 
     * @author Tianle Xu <xtl@xtlsoft.top>
     * @package LxdClient
     * @license MIT
     * 
     */
    
    namespace LxdClient;
    
    class Factory {
        
        public function server($server = "local"){
            return new Server($server);
        }
        
        public function blockStorage($pool = "/data"){
            return new BlockStorage($pool);
        }
        
        public function container($server, $name){
            return new Container($name, self::server($server));
        }
        
        public function blockVolume($pool, $name){
            return new BlockVolume($name, self::blockStorage($name));
        }
        
    }