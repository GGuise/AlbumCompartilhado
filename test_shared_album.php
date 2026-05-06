<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\User::where('email', 'guisegreg@gmail.com')->first();
$request = Illuminate\Http\Request::create('/admin/meu-album-compartilhado', 'GET');
$request->setUserResolver(function() use ($user) { return $user; });

$response = $app->handle($request);
if (isset($response->exception)) {
    echo "Exception: " . get_class($response->exception) . "\n" . $response->exception->getMessage() . "\n" . $response->exception->getTraceAsString();
} else {
    echo "Status: " . $response->getStatusCode() . "\n";
    echo substr($response->getContent(), 0, 500);
}
