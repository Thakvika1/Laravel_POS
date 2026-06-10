<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::firstOrCreate(
    ['email' => 'owner3@example.com'],
    [
        'name' => 'Owner2',
        'password' => Illuminate\Support\Facades\Hash::make('secret123'),
        'is_admin' => true,
    ]
);

$request = new Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'email' => 'owner3@example.com',
    'password' => 'secret123',
]);

$session = new Illuminate\Session\Store('test', new Illuminate\Session\ArraySessionHandler(60));
$request->setLaravelSession($session);

$controller = new App\Http\Controllers\AuthController();
$response = $controller->login($request);

var_dump($response->getStatusCode());
var_dump($response->getTargetUrl());
var_dump(Illuminate\Support\Facades\Auth::check());
