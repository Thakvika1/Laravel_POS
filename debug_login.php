<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = new App\Models\User([
    'name' => 'Owner',
    'email' => 'owner@example.com',
    'password' => Illuminate\Support\Facades\Hash::make('secret123'),
    'is_admin' => true,
]);
$user->save();

$request = Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'owner@example.com',
    'password' => 'secret123',
]);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);

echo $response->getStatusCode() . PHP_EOL;
echo $response->getContent() . PHP_EOL;
