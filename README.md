# experiments
Various experiments in building modern PHP based web application.

Running tests

	phpunit --configuration test/config.xml

Running application on build in server (in the project folder)

	php -S localhost:8000 -t .


Linie 38 należy zastąpić w pliku
\experiments\vendor\maximebf\cachecache\src\CacheCache\Backends\Memcached.php
- $this->memcache = new Memcache();
+ $this->memcache = new \Memcache(); 

