<?php

use App\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'site_title',
                'display_name' => 'Site Title',
                'value' => 'Eternizar',
            ],
            [
                'key' => 'site_copyright',
                'display_name' => 'Copyright Text',
                'value' => 'Backend By @ <a href="https://github.com/gguisesoares" target="_blank">Gregory Guise Soares</a>',
            ],
            [
                'key' => 'footer_description',
                'display_name' => 'Footer Description',
                'value' => 'Capturando momentos, eternizando memórias.',
            ],
            [
                'key' => 'social_facebook',
                'display_name' => 'Facebook URL',
                'value' => '#',
            ],
            [
                'key' => 'social_twitter',
                'display_name' => 'Twitter URL',
                'value' => '#',
            ],
            [
                'key' => 'social_instagram',
                'display_name' => 'Instagram URL',
                'value' => '#',
            ],
            [
                'key' => 'social_linkedin',
                'display_name' => 'LinkedIn URL',
                'value' => '#',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'display_name' => $setting['display_name'],
                    'slug' => str_slug($setting['display_name']),
                    'value' => $setting['value'],
                ]
            );
        }
    }
}
