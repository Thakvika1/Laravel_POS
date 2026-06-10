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

$result = Illuminate\Support\Facades\Auth::attempt([
    'email' => 'owner@example.com',
    'password' => 'secret123',
]);

var_dump($result);
var_dump(Illuminate\Support\Facades\Auth::check());
var_dump(Illuminate\Support\Facades\Auth::user()?->email);
