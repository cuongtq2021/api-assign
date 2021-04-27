<?php

namespace App\Http;
use NoahBuscher\Macaw\Macaw;

Macaw::get('/', 'App\Http\Controllers\PostController@index');
Macaw::get('/token', 'App\Http\Controllers\PostController@token');
Macaw::get('/orders', 'App\Http\Controllers\PostController@orders');
Macaw::get('/iphone-orders', 'App\Http\Controllers\PostController@assignedIphoneOrderList');
Macaw::get('/invoices', 'App\Http\Controllers\PostController@invoices');
Macaw::get('/create-order', 'App\Http\Controllers\PostController@createOrder');

Macaw::dispatch();
