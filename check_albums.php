<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$albums = \App\MeuAlbumCompartilhado::all();
foreach($albums as $album) {
    echo "ID: {$album->id} | Titulo: {$album->titulo} | Slug: {$album->slug}\n";
}
