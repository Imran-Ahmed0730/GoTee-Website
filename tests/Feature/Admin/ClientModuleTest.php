<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Client;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ClientModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_requires_at_least_one_contact_method(): void
    {
        $user = $this->userWithPermissions(['Client Add']);

        $this->actingAs($user)
            ->from(route('admin.client.create'))
            ->post(route('admin.client.store'), [
                'name' => 'Example Client',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.client.create'))
            ->assertSessionHasErrors(['email', 'phone']);

        $this->assertDatabaseCount('clients', 0);
    }

    public function test_client_can_be_created_with_either_contact_method(): void
    {
        $user = $this->userWithPermissions(['Client Add']);

        $this->actingAs($user)
            ->post(route('admin.client.store'), [
                'name' => 'Email Client',
                'email' => 'client@example.com',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.client.index'));

        $this->actingAs($user)
            ->post(route('admin.client.store'), [
                'name' => 'Phone Client',
                'phone' => '1234567890',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.client.index'));

        $this->assertDatabaseHas('clients', [
            'name' => 'Email Client',
            'email' => 'client@example.com',
            'phone' => null,
        ]);
        $this->assertDatabaseHas('clients', [
            'name' => 'Phone Client',
            'email' => null,
            'phone' => '1234567890',
        ]);
    }

    public function test_client_name_must_be_unique(): void
    {
        $user = $this->userWithPermissions(['Client Add']);

        $this->actingAs($user)
            ->post(route('admin.client.store'), [
                'name' => 'Duplicate Client',
                'email' => 'first@example.com',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.client.index'));

        $this->actingAs($user)
            ->from(route('admin.client.create'))
            ->post(route('admin.client.store'), [
                'name' => 'Duplicate Client',
                'phone' => '1234567890',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.client.create'))
            ->assertSessionHasErrors('name');

        $this->assertDatabaseCount('clients', 1);
    }

    public function test_database_rejects_duplicate_client_names(): void
    {
        Client::create([
            'name' => 'Duplicate Client',
            'email' => 'first@example.com',
            'status' => 1,
        ]);

        $this->expectException(QueryException::class);

        Client::create([
            'name' => 'Duplicate Client',
            'email' => 'second@example.com',
            'status' => 1,
        ]);
    }

    public function test_client_status_switch_can_deactivate_and_reactivate_a_client(): void
    {
        $user = $this->userWithPermissions(['Client Status Change']);
        $client = Client::create([
            'name' => 'Status Client',
            'email' => 'status@example.com',
            'status' => 1,
        ]);

        $this->actingAs($user)
            ->patch(route('admin.client.status', $client))
            ->assertRedirect();
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'status' => 0]);

        $this->actingAs($user)
            ->patch(route('admin.client.status', $client))
            ->assertRedirect();
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'status' => 1]);
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
