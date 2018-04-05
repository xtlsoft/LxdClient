# LxdClient
Lxd Client For PHP.

## License
MIT

## Author
Tianle Xu <xtl@xtlsoft.top>
Yes, it's me, not @idawnlight. The first commit is just a mistake (because we use the same server and I ran `git commit` without editing config).

## Usage
Example:

```php
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
    
    
```
