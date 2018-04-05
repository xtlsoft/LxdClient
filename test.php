<?php

    require_once "vendor/autoload.php";
    
    $server = LxdClient\Factory::server("local");
    
    $container = $server->create("testFromPHP")
        ->limitMemory("1GB")
        ->limitCpu(2, "90%")
        ->limitDisk("5GB")
        ->limitSwap(false)
        ->restart();
    
    $storage = LxdClient\Factory::blockStorage("/data/blockstorage/");
    
    $storage->create("testFromPHP", "20GB")
        ->mount()
        ->attach($container, "/data");
    
    