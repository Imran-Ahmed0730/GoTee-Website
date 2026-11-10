<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\BloodGroup;
use App\Models\Admin\Department;
use App\Models\Admin\Designation;
use App\Models\Admin\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class EmployeeModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<int, string>
     */
    private array $createdPublicImages = [];

    protected function tearDown(): void
    {
        foreach ($this->createdPublicImages as $imagePath) {
            if (is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        parent::tearDown();
    }

    public function test_employee_list_is_paginated_and_shows_sidebar_link(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee View']);

        foreach (range(1, 16) as $number) {
            $this->createEmployeeRecord('Employee '.$number, 'employee'.$number.'@example.com');
        }

        $this->actingAs($user)
            ->get(route('admin.employee.index'))
            ->assertOk()
            ->assertSee('Employee 16')
            ->assertSee('page=2')
            ->assertViewHas('items', fn ($items): bool => $items->first()->user->email === 'employee16@example.com')
            ->assertSee('href="'.route('admin.employee.index').'" class="admin-nav-link active"', false);

        $this->actingAs($user)
            ->get(route('admin.employee.index', ['page' => 2]))
            ->assertOk()
            ->assertViewHas('items', fn ($items): bool => $items->first()->user->email === 'employee1@example.com');
    }

    public function test_employee_list_can_be_filtered_by_department_designation_manager_and_team_visibility(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee View']);
        $department = Department::create(['name' => 'Design']);
        $otherDepartment = Department::create(['name' => 'Marketing']);
        $designation = Designation::create(['name' => 'Designer']);
        $otherDesignation = Designation::create(['name' => 'Manager']);
        $manager = $this->createEmployeeRecord('Reporting Manager', 'manager@example.com');
        $otherManager = $this->createEmployeeRecord('Other Manager', 'other-manager@example.com');
        $manager->update(['show_as_team' => 1]);
        $otherManager->update(['show_as_team' => 1]);

        $matchingEmployee = $this->createEmployeeRecord('Matching Employee', 'matching@example.com');
        $matchingEmployee->update([
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $manager->user_id,
            'show_as_team' => 1,
        ]);

        $employees = [
            'otherDepartment' => $this->createEmployeeRecord('Other Department Employee', 'other-department@example.com'),
            'otherDesignation' => $this->createEmployeeRecord('Other Designation Employee', 'other-designation@example.com'),
            'otherManager' => $this->createEmployeeRecord('Other Reporting Manager Employee', 'other-reporting-manager@example.com'),
            'hiddenFromTeam' => $this->createEmployeeRecord('Hidden Team Employee', 'hidden-team@example.com'),
        ];
        $employees['otherDepartment']->update([
            'department_id' => $otherDepartment->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $manager->user_id,
            'show_as_team' => 1,
        ]);
        $employees['otherDesignation']->update([
            'department_id' => $department->id,
            'designation_id' => $otherDesignation->id,
            'reporting_manager_id' => $manager->user_id,
            'show_as_team' => 1,
        ]);
        $employees['otherManager']->update([
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $otherManager->user_id,
            'show_as_team' => 1,
        ]);
        $employees['hiddenFromTeam']->update([
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $manager->user_id,
            'show_as_team' => 0,
        ]);

        $filters = [
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $manager->user_id,
            'show_as_team' => 1,
        ];

        $this->actingAs($user)
            ->get(route('admin.employee.index', $filters))
            ->assertOk()
            ->assertSee('name="department_id"', false)
            ->assertSee('name="designation_id"', false)
            ->assertSee('name="reporting_manager_id"', false)
            ->assertSee('name="show_as_team"', false)
            ->assertViewHas('items', fn ($items): bool => $items->total() === 1
                && $items->first()->is($matchingEmployee));

        foreach ([
            ['department_id' => $otherDepartment->id, 'employee' => $employees['otherDepartment']],
            ['designation_id' => $otherDesignation->id, 'employee' => $employees['otherDesignation']],
            ['reporting_manager_id' => $otherManager->user_id, 'employee' => $employees['otherManager']],
            ['show_as_team' => 0, 'employee' => $employees['hiddenFromTeam']],
        ] as $filter) {
            $employee = $filter['employee'];
            unset($filter['employee']);

            $this->actingAs($user)
                ->get(route('admin.employee.index', $filter))
                ->assertOk()
                ->assertViewHas('items', fn ($items): bool => $items->total() === 1
                    && $items->first()->is($employee));
        }
    }

    public function test_employee_can_be_created_with_private_nid_image_and_relationships(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Add']);
        $bloodGroup = BloodGroup::create(['name' => 'O+']);
        $department = Department::create(['name' => 'Design']);
        $designation = Designation::create(['name' => 'Designer']);
        $manager = $this->createEmployeeRecord('Existing Manager', 'manager@example.com');

        $this->actingAs($user)
            ->post(route('admin.employee.store'), $this->employeeInput([
                'first_name' => 'Avery',
                'last_name' => 'Employee',
                'email' => 'avery@example.com',
                'blood_group_id' => $bloodGroup->id,
                'department_id' => $department->id,
                'designation_id' => $designation->id,
                'reporting_manager_id' => $manager->user_id,
                'gender' => 3,
                'show_as_team' => 1,
                'profile_photo_path' => UploadedFile::fake()->image('profile.png'),
                'nid_image' => UploadedFile::fake()->image('nid.png'),
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.employee.index'));

        $employee = Employee::query()
            ->with('user')
            ->whereHas('user', fn ($query) => $query->where('email', 'avery@example.com'))
            ->firstOrFail();

        $this->assertSame('Avery', $employee->user->first_name);
        $this->assertSame('Employee', $employee->user->last_name);
        $this->assertSame('avery.2025', $employee->user->username);
        $this->assertSame('avery@example.com', $employee->user->email);
        $this->assertSame(2, $employee->user->role);
        $this->assertSame(sprintf('2025%05d', $employee->user_id), $employee->employee_id);
        $this->assertStringStartsWith('uploads/employees/profile/', $employee->user->profile_photo_path);
        $this->assertFileExists(public_path($employee->user->profile_photo_path));
        $this->createdPublicImages[] = public_path($employee->user->profile_photo_path);
        $this->assertSame(3, $employee->gender);
        $this->assertTrue($employee->show_as_team);
        $this->assertSame('Active', $employee->status);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'blood_group_id' => $bloodGroup->id,
            'department_id' => $department->id,
            'designation_id' => $designation->id,
            'reporting_manager_id' => $manager->user_id,
            'nid_no' => 'ID-000123',
        ]);
        Storage::disk('local')->assertExists($employee->nid_image);
    }

    public function test_employee_update_keeps_existing_private_image_when_no_replacement_is_uploaded(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Update']);
        $employee = $this->createEmployeeRecord('Existing Employee', 'existing@example.com');
        $existingImage = $employee->nid_image;

        $this->actingAs($user)
            ->patch(route('admin.employee.update', $employee), $this->employeeInput([
                'first_name' => 'Updated',
                'last_name' => 'Employee',
                'email' => 'updated@example.com',
                'nid_image' => null,
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.employee.index'));

        $employee->refresh()->load('user');

        $this->assertSame('Updated', $employee->user->first_name);
        $this->assertSame('Employee', $employee->user->last_name);
        $this->assertSame('updated@example.com', $employee->user->email);
        $this->assertSame($existingImage, $employee->nid_image);
        Storage::disk('local')->assertExists($existingImage);
    }

    public function test_profile_image_and_nid_image_are_replaced_using_the_helper_and_private_disk(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Update']);
        $employee = $this->createEmployeeRecord('Replace Employee', 'replace@example.com');
        $oldProfilePhotoPath = $this->createPublicProfileImage('old-profile.png');
        $oldNidImagePath = 'employees/nid/old-nid.png';
        Storage::disk('local')->put($oldNidImagePath, 'old private NID image');
        $employee->user->forceFill(['profile_photo_path' => $oldProfilePhotoPath])->save();
        $employee->update(['nid_image' => $oldNidImagePath]);

        $this->createdPublicImages[] = public_path($oldProfilePhotoPath);

        $this->actingAs($user)
            ->patch(route('admin.employee.update', $employee), $this->employeeInput([
                'first_name' => 'Replace',
                'last_name' => 'Employee',
                'email' => 'replace@example.com',
                'nid_no' => $employee->nid_no,
                'profile_photo_path' => UploadedFile::fake()->image('replacement-profile.png'),
                'nid_image' => UploadedFile::fake()->image('replacement-nid.png'),
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.employee.index'));

        $employee->refresh()->load('user');

        $this->assertNotSame($oldProfilePhotoPath, $employee->user->profile_photo_path);
        $this->assertFileDoesNotExist(public_path($oldProfilePhotoPath));
        $this->createdPublicImages[] = public_path($employee->user->profile_photo_path);
        $this->assertNotSame($oldNidImagePath, $employee->nid_image);
        Storage::disk('local')->assertMissing($oldNidImagePath);
        Storage::disk('local')->assertExists($employee->nid_image);
    }

    public function test_replacing_employee_nid_image_removes_the_previous_private_file(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Update']);
        $employee = $this->createEmployeeRecord('Replace Employee', 'replace@example.com');
        $previousImage = $employee->nid_image;

        $this->actingAs($user)
            ->patch(route('admin.employee.update', $employee), $this->employeeInput([
                'first_name' => 'Replace',
                'last_name' => 'Employee',
                'email' => 'replace@example.com',
                'nid_no' => $employee->nid_no,
                'nid_image' => UploadedFile::fake()->image('replacement-nid.png'),
            ]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.employee.index'));

        $updatedImage = $employee->refresh()->nid_image;

        $this->assertNotSame($previousImage, $updatedImage);
        Storage::disk('local')->assertMissing($previousImage);
        Storage::disk('local')->assertExists($updatedImage);
    }

    public function test_employee_requires_a_valid_gender_and_nid_image_on_creation(): void
    {
        $user = $this->userWithPermissions(['Employee Add']);

        $this->actingAs($user)
            ->from(route('admin.employee.create'))
            ->post(route('admin.employee.store'), $this->employeeInput([
                'gender' => 0,
                'profile_photo_path' => null,
                'nid_image' => null,
            ]))
            ->assertRedirect(route('admin.employee.create'))
            ->assertSessionHasErrors(['gender', 'nid_image', 'profile_photo_path']);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('employees', 0);
    }

    public function test_employee_status_and_team_visibility_can_be_changed_with_permissions(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Status Change', 'Employee Update']);
        $employee = $this->createEmployeeRecord('Toggle Employee', 'toggle@example.com');

        $this->actingAs($user)
            ->patch(route('admin.employee.status', $employee))
            ->assertRedirect();
        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'status' => 0]);

        $this->actingAs($user)
            ->patch(route('admin.employee.show-as-team.update', $employee), ['show_as_team' => 1])
            ->assertRedirect();
        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'show_as_team' => 1]);
    }

    public function test_employee_cannot_be_managed_without_the_required_permission(): void
    {
        $user = $this->userWithPermissions([]);

        $this->actingAs($user)
            ->get(route('admin.employee.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.employee.create'))
            ->assertForbidden();
    }

    public function test_employee_deletion_removes_its_user_and_private_nid_image(): void
    {
        Storage::fake('local');
        $user = $this->userWithPermissions(['Employee Delete']);
        $employee = $this->createEmployeeRecord('Delete Employee', 'delete@example.com');
        $employeeUser = $employee->user;
        $imagePath = $employee->nid_image;

        $this->actingAs($user)
            ->delete(route('admin.employee.destroy', $employee))
            ->assertRedirect(route('admin.employee.index'));

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
        $this->assertDatabaseMissing('users', ['id' => $employeeUser->id]);
        Storage::disk('local')->assertMissing($imagePath);
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

    private function createEmployeeRecord(string $name, string $email): Employee
    {
        $user = User::factory()->create([
            'first_name' => explode(' ', $name, 2)[0],
            'last_name' => explode(' ', $name, 2)[1] ?? null,
            'email' => $email,
            'role' => 2,
        ]);
        $imagePath = 'employee-nids/'.str_replace('@', '-', $email).'.png';
        Storage::disk('local')->put($imagePath, 'private nid image');

        return Employee::create([
            'user_id' => $user->id,
            'employee_id' => sprintf('2025%05d', $user->id),
            'father_name' => 'Father',
            'mother_name' => 'Mother',
            'present_address' => '123 Example Street',
            'permanent_address' => '123 Example Street',
            'contacting_person_first_name' => 'Contact',
            'contacting_person_phone' => '1234567890',
            'contacting_person_relation' => 'Family',
            'phone' => '1234567890',
            'gender' => 1,
            'nid_no' => 'ID-'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
            'nid_image' => $imagePath,
            'joining_date' => '2025-01-01',
            'salary' => '50000.00',
            'status' => 1,
            'show_as_team' => 0,
        ]);
    }

    private function createPublicProfileImage(string $filename): string
    {
        $directory = public_path('uploads/employees/profile');

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $relativePath = 'uploads/employees/profile/test-'.bin2hex(random_bytes(8)).'-'.$filename;
        file_put_contents(public_path($relativePath), 'profile image');

        return $relativePath;
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function employeeInput(array $overrides = []): array
    {
        return array_replace([
            'first_name' => 'Employee',
            'last_name' => 'Name',
            'email' => 'employee@example.com',
            'father_name' => 'Father',
            'mother_name' => 'Mother',
            'present_address' => '123 Example Street',
            'permanent_address' => '123 Example Street',
            'contacting_person_first_name' => 'Contact',
            'contacting_person_phone' => '1234567890',
            'contacting_person_relation' => 'Family',
            'phone' => '1234567890',
            'gender' => 1,
            'blood_group_id' => null,
            'department_id' => null,
            'designation_id' => null,
            'nid_no' => 'ID-000123',
            'nid_image' => UploadedFile::fake()->image('nid.png'),
            'profile_photo_path' => null,
            'joining_date' => '2025-01-01',
            'salary' => '50000.00',
            'status' => 1,
            'show_as_team' => 0,
        ], $overrides);
    }
}
