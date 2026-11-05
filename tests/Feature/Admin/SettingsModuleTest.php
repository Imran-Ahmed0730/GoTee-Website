<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Setting;
use App\Models\User;
use Database\Seeders\Admin\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SettingsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_each_settings_section(): void
    {
        $user = $this->userWithPermissions([
            'Settings View',
            'Settings Site',
            'Settings Logo & Favicon',
            'Settings Contact',
            'Settings Social Media',
            'Settings Smtp',
        ]);
        $this->seed(SettingSeeder::class);

        $this->actingAs($user)
            ->get(route('admin.setting.index'))
            ->assertOk()
            ->assertSee('business_name')
            ->assertSee('site_name')
            ->assertSee('GoTEE');

        foreach (['general', 'logos-favicon', 'contact', 'social-media', 'smtp'] as $section) {
            $this->actingAs($user)
                ->get(route('admin.setting.edit', $section))
                ->assertOk();
        }

        $this->actingAs($user)
            ->get(route('admin.setting.edit', 'general'))
            ->assertSee('id="settings-submenu"', false)
            ->assertSee('aria-expanded="false" aria-controls="settings-submenu"', false)
            ->assertSee('id="settings-submenu" class="admin-submenu" hidden', false)
            ->assertSee('href="'.route('admin.setting.edit', 'contact').'"', false)
            ->assertSee('href="'.route('admin.setting.edit', 'smtp').'"', false);
    }

    public function test_general_contact_and_social_settings_are_updated(): void
    {
        $user = $this->userWithPermissions([
            'Settings Update',
            'Settings Site',
            'Settings Contact',
            'Settings Social Media',
        ]);

        $this->actingAs($user)
            ->patch(route('admin.setting.update', 'general'), [
                'site_name' => 'GoTee Studio',
                'business_name' => 'GoTee Creative',
                'site_url' => 'https://gotee.example',
                'short_bio' => 'Creative services for growing brands.',
                'meta_description' => 'Design and development services.',
                'developed_by' => 'GoTee',
                'developed_by_url' => 'https://gotee.example',
            ])
            ->assertRedirect(route('admin.setting.edit', 'general'));

        $this->assertDatabaseHas('settings', ['key' => 'site_name', 'value' => 'GoTee Studio']);
        $this->assertDatabaseHas('settings', ['key' => 'business_name', 'value' => 'GoTee Creative']);

        $this->actingAs($user)
            ->patch(route('admin.setting.update', 'contact'), [
                'address' => '123 Example Street',
                'email' => 'hello@example.com',
                'phone' => '+880123456789',
                'whatsapp' => '+880123456789',
                'opening_time' => '09:00 AM',
                'closing_time' => '06:00 PM',
                'closed_on' => ['5', '6'],
            ])
            ->assertRedirect(route('admin.setting.edit', 'contact'));

        $this->assertDatabaseHas('settings', [
            'key' => 'closed_on',
            'value' => '["5","6"]',
        ]);

        $this->actingAs($user)
            ->patch(route('admin.setting.update', 'social-media'), [
                'facebook_url' => 'https://facebook.com/gotee',
                'x_url' => '',
                'instagram_url' => '',
                'linkedin_url' => '',
                'youtube_url' => '',
            ])
            ->assertRedirect(route('admin.setting.edit', 'social-media'));

        $this->assertDatabaseHas('settings', [
            'key' => 'facebook_url',
            'value' => 'https://facebook.com/gotee',
        ]);
    }

    public function test_logo_uploads_use_the_shared_image_path_helper_and_replace_previous_image(): void
    {
        $user = $this->userWithPermissions(['Settings Update', 'Settings Logo & Favicon']);

        $this->actingAs($user)
            ->patch(route('admin.setting.update', 'logos-favicon'), [
                'site_logo' => UploadedFile::fake()->image('site-logo.png'),
            ])
            ->assertRedirect(route('admin.setting.edit', 'logos-favicon'));

        $previousImagePath = Setting::query()->where('key', 'site_logo')->value('value');
        $this->assertIsString($previousImagePath);
        $this->assertFileExists(public_path($previousImagePath));

        try {
            $this->actingAs($user)
                ->patch(route('admin.setting.update', 'logos-favicon'), [
                    'site_logo' => UploadedFile::fake()->image('updated-site-logo.png'),
                ])
                ->assertRedirect(route('admin.setting.edit', 'logos-favicon'));

            $updatedImagePath = Setting::query()->where('key', 'site_logo')->value('value');
            $this->assertIsString($updatedImagePath);
            $this->assertNotSame($previousImagePath, $updatedImagePath);
            $this->assertFileDoesNotExist(public_path($previousImagePath));
            $this->assertFileExists(public_path($updatedImagePath));
        } finally {
            if (isset($updatedImagePath) && is_file(public_path($updatedImagePath))) {
                unlink(public_path($updatedImagePath));
            } elseif (is_file(public_path($previousImagePath))) {
                unlink(public_path($previousImagePath));
            }
        }
    }

    public function test_settings_sections_require_their_matching_permission(): void
    {
        $user = $this->userWithPermissions(['Settings View']);

        $this->actingAs($user)
            ->get(route('admin.setting.edit', 'general'))
            ->assertForbidden();
    }

    public function test_smtp_settings_validate_before_writing_environment_values(): void
    {
        $user = $this->userWithPermissions(['Settings Update', 'Settings Smtp']);

        $this->actingAs($user)
            ->from(route('admin.setting.edit', 'smtp'))
            ->patch(route('admin.setting.update', 'smtp'), [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => '',
                'MAIL_PORT' => '70000',
                'MAIL_FROM_ADDRESS' => 'not-an-email',
                'MAIL_FROM_NAME' => '',
            ])
            ->assertRedirect(route('admin.setting.edit', 'smtp'))
            ->assertSessionHasErrors(['MAIL_HOST', 'MAIL_PORT', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME']);
    }

    /**
     * @param  array<int, string>  $permissionNames
     */
    private function userWithPermissions(array $permissionNames): User
    {
        $user = User::factory()->create(['role' => 1]);

        foreach ($permissionNames as $permissionName) {
            $user->givePermissionTo(Permission::findOrCreate($permissionName, 'web'));
        }

        return $user;
    }
}
