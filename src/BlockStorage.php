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
    
    class BlockStorage {
        
        protected $pool = "";
        
        public function __construct($pool){
            $this->pool = $pool;
        }
        
        public function create($name, $size="5GB"){
            
            $this->run("truncate", "-s", $size, $this->pool . $name . ".img");
            
            $this->run("mkfs.ext4", $this->pool . $name . ".img");
            
            $this->run("mkdir", $this->pool . $name . ".img.d");
            
            $this->mount($name);
            
            return new BlockVolume($name, $this);
            
        }
        
        public function mount($name){
            
            $this->run("mount", $this->pool . $name . ".img", $this->pool . $name . ".img.d");
            
            $this->run("chmod", "7777", $this->pool . $name . ".img.d");
            
            return $this;
            
        }
        
        public function umount($name){
            
            $this->run("umount", $this->pool . $name . ".img");
            
            return $this;
            
        }
        
        public function volume($name){
            
            return new BlockVolume($name, $this);
            
        }
        
        public function attach($name, Container $container, $path = "/data"){
            $container->addDisk("BlockStorage-" . $name, $this->getMountPath($name), $path);
            
            file_put_contents($this->pool . $name . ".img.attach", serialize($container));
            
            return $this;
        }
        
        public function disattach($name){
            
            $container = file_get_contents($this->pool . $name . ".img.attach");
            $container = unserialize($container);
            
            $container->delDevice("BlockStorage-" . $name);
            
            return $this;
            
        }
        
        public function remove($name){
            
            $this->disattach($name);
            $this->umount($name);
            
            $this->run("rm -rf", $this->pool . $name . ".*");
            
        }
        
        public function mountAll(){
            
            foreach(glob($this->pool . "*.img") as $v){
                $name = substr($v, 0, strlen($v) - 4);
                $this->mount($name);
            }
            return $this;
            
        }
        
        public function getMountPath($name){
            return ($this->pool . $name . ".img.d");
        }
        
        protected function run(){
            
            $args = func_get_args();
            
            $args = implode(" ", $args);
            
            $rslt = shell_exec($args);
            
            return $rslt;
            
        }
        
    }