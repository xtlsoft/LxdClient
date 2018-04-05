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
    
    class BlockVolume {
        
        public $name;
        public $parent;
        
        public function __construct($name, $parent){
            
            $this->name = $name;
            $this->parent = $parent;
            
        }
        
        public function __call($name, $args){
            
            $arg = [$this->name];
            foreach($args as $v){
                $arg[] = $v;
            }
            
            $rslt = call_user_func_array([$this->parent, $name], $arg);
            
            if($rslt instanceof BlockStorage){
                return $this;
            }else{
                return $rslt;
            }
            
        }
        
    }