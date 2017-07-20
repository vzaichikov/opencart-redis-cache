# opencart-redis-cache

system/library/cache/redis.php

to enable you need to change caching engine in system/config/default.php on line 35:
// Cache
$_['cache_type']           = 'redis'; // apc, file or mem
