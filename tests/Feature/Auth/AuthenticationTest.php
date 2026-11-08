<?php

namespace Tests\Feature\Auth;

use App\Models\Admin\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_active_employees_can_authenticate(): void
    {
        $user = User::factory()->create(['role' => 2]);
        $this->createEmployee($user, 1);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_inactive_employees_cannot_authenticate(): void
    {
        $user = User::factory()->create(['role' => 2]);
        $this->createEmployee($user, 0);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login')->assertSessionHasErrors('email');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    private function createEmployee(User $user, int $status): Employee
    {
        return Employee::create([
            'user_id' => $user->id,
            'employee_id' => sprintf('2024%05d', $user->id),
            'father_name' => 'Employee Father',
            'mother_name' => 'Employee Mother',
            'phone' => '1234567890',
            'gender' => 1,
            'present_address' => 'Present address',
            'permanent_address' => 'Permanent address',
            'contacting_person_first_name' => 'Emergency',
            'contacting_person_phone' => '0987654321',
            'contacting_person_relation' => 'Relative',
            'joining_date' => '2024-01-01',
            'salary' => '50000.00',
            'status' => $status,
        ]);
    }
}
