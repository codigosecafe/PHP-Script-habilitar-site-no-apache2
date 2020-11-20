#!/usr/bin/env php
<?php

if ( !is_dir( __DIR__.'/vendor' ) ) {
   echo shell_exec('cd '.__DIR__.' && composer install');  
   echo shell_exec('cd '.__DIR__.' && php create_file.php');
}


require __DIR__.'/vendor/autoload.php';


use App\AmbCommand;
use App\AmbSSLCommand;
use Symfony\Component\Console\Application;


define("PATH_PROJECT",  __DIR__);
define("PATH_STOPRAGE", PATH_PROJECT.'/storage');
define("PATH_SITES_AVAILABLE", '/etc/apache2/sites-available');


$application = new Application();
$application->add(new AmbCommand());
$application->add(new AmbSSLCommand());
$application->run();
