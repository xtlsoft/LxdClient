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
    
    class Server {
            
        protected $name;
            
        public function __construct($name){
            
            $this->name = $name . ":";
            
        }
        
        public function create($name, $image = "ubuntu:16.04"){
            
            $this->run("launch", $image, $this->name . $name);
            return new Container($name, $this);
            
        }
        
        public function container($name){
            
            return new Container($name, $this);
            
        }
        
        public function delete($name){
            
            $this->run("delete", $this->name . $name, "-f");
            return $this;
            
        }
        
        public function setConfig($name, $key, $value){
            
            $this->run("config", "set", $this->name . $name, $key, $value);
            return $this;
            
        }
        
        public function getConfig($name, $key){
            
            return trim($this->run("config", "get", $this->name . $name, $key));
            
        }
        
        public function addDevice($name, $devname, $type="disk", $config){
            
            $this->run("config", "device", "add", $this->name . $name, $devname, $type, $config);
            return $this;
            
        }
        
        public function delDevice($name, $devname){
            
            $this->run("config", "device", "remove", $this->name . $name, $devname);
            return $this;
            
        }
        
        public function configDevice($name, $devname, $key, $value){
            
            $this->run("config", "device", "set", $this->name . $name, $devname, $key, $value);
            return $this;
            
        }
        
        public function limitMemory($name, $size, $force = true){
            
            $this->setConfig($name, "limits.memory", $size);
            
            $this->setConfig($name, "limits.memory.enforce", ($force ? "hard" : "soft"));
            
            return $this;
            
        }
        
        public function limitSwap($name, $on = true){
            
            $this->setConfig($name, "limits.memory.swap", ($on ? "true" : "false"));
            
            return $this;
            
        }
        
        public function addDisk($name, $diskname, $path, $mount = "/data"){
            
            $this->addDevice($name, $diskname, "disk", "source=$path path=$mount");
            
            return $this;
            
        }
        
        public function limitCpu($name, $cpu, $allowance = "100%"){
            
            $this->setConfig($name, "limits.cpu", $cpu);
            $this->setConfig($name, "limits.cpu.allowance", $allowance);
            
            return $this;
            
        }
        
        public function limitDisk($name, $size){
            
            $this->configDevice($name, "root", "size", $size);
            
            return $this;
            
        }
        
        public function restart($name){
            
            $this->run("restart", $this->name . $name);
            
            return $this;
            
        }
        
        public function stop($name){
            
            $this->run("stop", $this->name . $name);
            
            return $this;
            
        }
        
        public function start($name){
            
            $this->run("start", $this->name . $name);
            
            return $this;
            
        }
        
        protected function run(){
            
            $args = func_get_args();
            
            $args = implode(" ", $args);
            
            $rslt = shell_exec("lxc $args");
            
            return $rslt;
            
        }
        
    }