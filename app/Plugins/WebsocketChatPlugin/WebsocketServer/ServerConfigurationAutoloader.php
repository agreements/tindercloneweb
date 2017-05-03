<?php

require_once __DIR__."/../Workerman/Autoloader.php";
require_once __DIR__."/../PHPSocket_IO/autoload.php";


require __DIR__.'/../../../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../../../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

/* including laravel vendor for database elloquent */
/*
require_once __DIR__."/../../../../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;  

$db_config = include __DIR__. "/database.php"; 

$capsule = new Capsule; 
$capsule->addConnection($db_config);
$capsule->setAsGlobal();
$capsule->bootEloquent();


*/

$ca_file_path = __DIR__."/../../../../storage/intermediate.ca";
$cert_file_path = __DIR__."/../../../../storage/certificate.pem";
