<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$items = [
    ['key' => 'social_facebook', 'display_name' => 'Facebook URL', 'value' => '#'],
    ['key' => 'social_twitter', 'display_name' => 'Twitter URL', 'value' => '#'],
    ['key' => 'social_instagram', 'display_name' => 'Instagram URL', 'value' => '#'],
    ['key' => 'social_linkedin', 'display_name' => 'LinkedIn URL', 'value' => '#'],
    ['key' => 'footer_description', 'display_name' => 'Descrição do Rodapé', 'value' => 'Capturando momentos, eternizando memórias.'],
];

foreach ($items as $item) {
    if (!\App\Setting::where('key', $item['key'])->exists()) {
        \App\Setting::create([
            'key' => $item['key'],
            'slug' => str_slug($item['key']),
            'display_name' => $item['display_name'],
            'value' => $item['value']
        ]);
        echo "Created: " . $item['key'] . "\n";
    } else {
        echo "Exists: " . $item['key'] . "\n";
    }
}
