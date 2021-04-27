<?php
require_once(__DIR__ . '/vendor/autoload.php');

use Dotenv\Dotenv;

$dotenv = DotEnv::createImmutable(__DIR__);
$dotenv->safeLoad();

require_once(__DIR__ . '/App/Http/Router.php');
