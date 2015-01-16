<?php
// Report all PHP errors
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require 'vendor/autoload.php';

use Fyndiq\Api as Api;

// Initialize a new Session and instanciate an Api object
Api::init('username', 'api_key', true);

$api = new Api\Fyndiq;

print_r($api->product());