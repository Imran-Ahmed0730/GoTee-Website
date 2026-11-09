<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'address', 'value' => 'Dhaka, Bangladesh'],
            ['key' => 'business_name', 'value' => 'GoTEE'],
            ['key' => 'closed_on', 'value' => '["1", "2"]'],
            ['key' => 'closing_time', 'value' => '06:00 PM'],
            ['key' => 'developed_by', 'value' => 'GoTee'],
            ['key' => 'developed_by_url', 'value' => 'https://gotee-bd.com'],
            ['key' => 'email', 'value' => 'info@gotee.com'],
            ['key' => 'facebook_url', 'value' => ''],
            ['key' => 'instagram_url', 'value' => ''],
            ['key' => 'linkedin_url', 'value' => ''],
            ['key' => 'meta_description', 'value' => 'This is a boiler template for GoTEE'],
            ['key' => 'opening_time', 'value' => '09:00 AM'],
            ['key' => 'phone1', 'value' => '0123456789'],
            ['key' => 'phone2', 'value' => '0123456789'],
            ['key' => 'short_bio', 'value' => 'Building cutting edge technologies'],
            ['key' => 'site_dark_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'site_favicon', 'value' => 'backend/assets/img/AdminLTELogo.png'],
            ['key' => 'site_footer_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'site_logo', 'value' => 'backend/assets/img/AdminLTEFullLogo.png'],
            ['key' => 'site_name', 'value' => 'GoTEE'],
            ['key' => 'site_url', 'value' => ''],
            ['key' => 'whatsapp', 'value' => '0123456789'],
            ['key' => 'x_url', 'value' => ''],
            ['key' => 'youtube_url', 'value' => ''],
            ['key' => 'map_location', 'value' => ''],
            // Add more key-value pairs here
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']],
            );
        }
    }
}
