Opencart Redis Cache Adaptor  
--------------

🔥Utilizes Redis as key-value storage for OpenCart 2.x (and possibly 1.5.4.6)

Usage
-------------
1. You need to put this to system/library/cache/redis.php
2. You need to have Redis daemon installed and running
3. You need to change caching engine in system/config/default.php to $_['cache_type'] = 'redis';
