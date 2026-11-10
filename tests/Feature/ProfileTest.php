<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/admin/profile');

        $response
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('Profile settings');
    }

    public function test_profile_page_displays_the_saved_profile_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/profile.jpg', 'profile photo');
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/profile.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/admin/profile');

        $response
            ->assertOk()
            ->assertSee(Storage::disk('public')->url('profile-photos/profile.jpg'));
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'username' => 'test.user',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'profile-updated')
            ->assertRedirect('/admin/profile');

        $user->refresh();

        $this->assertSame('Test', $user->first_name);
        $this->assertSame('User', $user->last_name);
        $this->assertSame('test.user', $user->username);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'username' => $user->username,
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_duplicate_username_cannot_be_used_in_profile(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($user)->patch('/admin/profile', [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $other->username,
            'email' => $user->email,
        ])->assertSessionHasErrors('username');

        $this->assertNotSame($other->username, $user->refresh()->username);
    }

    public function test_generated_usernames_are_unique_for_the_same_first_name_and_year(): void
    {
        $this->travelTo(now()->setDate(2024, 1, 1));
        $first = User::factory()->create(['first_name' => 'Jane']);
        $second = User::factory()->create(['first_name' => 'Jane']);

        $this->assertSame('jane.2024', $first->username);
        $this->assertSame('jane.2024.1', $second->username);
    }

    public function test_user_can_upload_a_profile_photo_and_replace_the_existing_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/old-photo.jpg', 'old photo');
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/old-photo.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'profile_photo' => UploadedFile::fake()->createWithContent(
                    'new-photo.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/l2cAAAAASUVORK5CYII='),
                ),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $user->refresh();

        $this->assertNotSame('profile-photos/old-photo.jpg', $user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
        Storage::disk('public')->assertMissing('profile-photos/old-photo.jpg');
    }

    public function test_user_can_remove_their_profile_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/photo.jpg', 'profile photo');
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/photo.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'remove_profile_photo' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertNull($user->refresh()->profile_photo_path);
        Storage::disk('public')->assertMissing('profile-photos/photo.jpg');
    }

    public function test_invalid_profile_photo_is_rejected_without_changing_the_existing_photo(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/current.jpg', 'profile photo');
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/current.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/admin/profile')
            ->patch('/admin/profile', [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'email' => $user->email,
                'profile_photo' => UploadedFile::fake()->create('not-an-image.txt', 10, 'text/plain'),
            ]);

        $response
            ->assertSessionHasErrors('profile_photo')
            ->assertRedirect('/admin/profile');

        $this->assertSame('profile-photos/current.jpg', $user->refresh()->profile_photo_path);
        Storage::disk('public')->assertExists('profile-photos/current.jpg');
    }

    public function test_user_can_delete_their_account(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('profile-photos/profile.jpg', 'profile photo');
        $user = User::factory()->create([
            'profile_photo_path' => 'profile-photos/profile.jpg',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/admin/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
        Storage::disk('public')->assertMissing('profile-photos/profile.jpg');
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/admin/profile')
            ->delete('/admin/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->fresh());
    }
}
