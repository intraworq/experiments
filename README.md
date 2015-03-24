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


Klient do sql lite
https://github.com/sqlitebrowser/sqlitebrowser/releases


ORM
http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/getting-started.html

XHPROF
Download php_xhprof-0.10.6-5.5-nts-vc11-x86.zip from http://windows.php.net/downloads/pecl/releases/xhprof/0.10.6
Configure php.ini

[xhprof]
extension=php_xhprof.dll
xhprof.output_dir="<output dir with profiling files results>"

To run profiling type <domain>/index_dev.php

To show profiling results: http://<localhost>/vendor/facebook/xhprof/xhprof_html/index.php

[selenium]
Run seleenium server: java -jar selenium-server-standalone-2.45.0.jar