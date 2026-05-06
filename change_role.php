<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\User::where('email', 'guisegreg@gmail.com')->first();
if ($user) {
    $user->detachRoles();
    $user->attachRole('contributor');
    echo "Cargo do guisegreg alterado para contributor.\n";
}
