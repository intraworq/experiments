# experiments
Various experiments in building modern PHP based web application.

Running tests

	phpunit --configuration test/config.xml

Running application on build in server (in the project folder)

	php -S localhost:8000 -t .

MEMCACHE

Linie 38 należy zastąpić w pliku
\experiments\vendor\maximebf\cachecache\src\CacheCache\Backends\Memcached.php
- $this->memcache = new Memcache();
+ $this->memcache = new \Memcache(); 

Aby uruchomić mem chache należy uruchomić usługę
poprzez oddpalenie memcached.exe w znajdującego się w memchache/services

Do php należy dołączyć bibliotekę obsługującą cachowanie.
Biblioteka z przykładami znajduje sie w memchache/lib ( może być wymagana inna w zależności od wersji php. Ta jest dla wersji 32 bit php i no-self)
Pomocny link: http://zurmo.org/wiki/installing-memcache-on-windows

Po uruchomieniu tych dwuch rzeczy obsługujemy to w aplikacji w poniższy sposób:

Ustawianie zmiennych:
$app->cache->set('object', $object);

Pobieranie zmiennych:
$app->cache->get('object');
Przykład zastosowania jest w index.php (site /setCache i /cache)

Dokumentacje:
http://maximebf.github.io/CacheCache/
http://php.net/manual/en/book.memcache.php

KONIEC MEMCACHE