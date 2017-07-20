<?php
	namespace Cache;
	class Redis {
		private $expire;
		private $rcache;
		
		const CACHEDUMP_LIMIT = 9999;
		
		public function __construct($expire) {
			if (!defined('REDIS_DATABASE')){
				define('REDIS_DATABASE', '1');
			}
			
			if (!defined('REDIS_SOCKET')){
				define('REDIS_SOCKET', '/var/run/redis/redis.sock');
			}
			
			if (!defined('REDIS_HOST')){
				define('REDIS_HOST', '127.0.0.1');
			}
			
			if (!defined('REDIS_PORT')){
				define('REDIS_PORT', '6379');
			}
			
			if (!defined('CACHE_PREFIX')){
				define('CACHE_PREFIX', 'oc_');
			}
			
			$this->expire = $expire;
			
			$this->rcache = new \Redis();
			if (@$this->rcache->pconnect(REDIS_SOCKET)){
				$this->rcache->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
				$this->rcache->select(REDIS_DATABASE);				
				} elseif(@$this->rcache->pconnect(REDIS_HOST, REDIS_PORT)) {
				$this->rcache->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_PHP);
				$this->rcache->select(REDIS_DATABASE);
			}				
		}
		
		public function get($key) {
			return $this->rcache->get(CACHE_PREFIX . $key);
		}
		
		public function set($key, $value) {
			return $this->rcache->set(CACHE_PREFIX . $key, $value, Array('nx', 'ex' => $this->expire));			
		}
		
		public function flush() {
			$this->rcache->select(REDIS_DATABASE);
			return $this->rcache->flushDb();			
		}
		
		public function delete($key) {
		
			$_key = CACHE_PREFIX . $key;
		
			$it = null;
			$arr_keys = $this->rcache->scan($it, "$_key*", self::CACHEDUMP_LIMIT);
			
			foreach($arr_keys as $str_key) {				
				if (strpos($str_key, CACHE_PREFIX . $key) === 0) {
				
					$this->rcache->delete($str_key);
				}
			}			
		}
	}		