<?php

namespace App\Services\Admin;

use App\Models\Admin\BloodGroup;
use App\Models\Admin\Department;
use App\Models\Admin\Designation;
use App\Models\Admin\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class EmployeeService
{
    /**
     * @param  array{search?: string, status?: string, department_id?: string, designation_id?: string, reporting_manager_id?: string, show_as_team?: string}  $filters
     */
    public function getAllEmployees(array $filters): LengthAwarePaginator
    {
        return Employee::query()
            ->with([
                'user:id,first_name,last_name,email,profile_photo_path',
                'bloodGroup:id,name',
                'department:id,name',
                'designation:id,name',
                'reportingManager:id,first_name,last_name',
            ])
            ->when(isset($filters['search']) && $filters['search'] !== '', function (Builder $query) use ($filters): void {
                $search = $filters['search'];

                $query->where(function (Builder $query) use ($search): void {
                    $query->where('phone', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function (Builder $query) use ($search): void {
                            $query->where('first_name', 'like', '%'.$search.'%')
                                ->orWhere('last_name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn (Builder $query): Builder => $query->where('status', (int) $filters['status']),
            )
            ->when(
                isset($filters['department_id']) && $filters['department_id'] !== '',
                fn (Builder $query): Builder => $query->where('department_id', (int) $filters['department_id']),
            )
            ->when(
                isset($filters['designation_id']) && $filters['designation_id'] !== '',
                fn (Builder $query): Builder => $query->where('designation_id', (int) $filters['designation_id']),
            )
            ->when(
                isset($filters['reporting_manager_id']) && $filters['reporting_manager_id'] !== '',
                fn (Builder $query): Builder => $query->where('reporting_manager_id', (int) $filters['reporting_manager_id']),
            )
            ->when(
                isset($filters['show_as_team']) && $filters['show_as_team'] !== '',
                fn (Builder $query): Builder => $query->where('show_as_team', (int) $filters['show_as_team']),
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @return array{
     *     departments: Collection<int, Department>,
     *     designations: Collection<int, Designation>,
     *     reportingManagers: Collection<int, Employee>
     * }
     */
    public function getFilterOptions(): array
    {
        return [
            'departments' => Department::query()->orderBy('name')->get(),
            'designations' => Designation::query()->orderBy('name')->get(),
            'reportingManagers' => Employee::query()
                ->with('user:id,first_name,last_name')
                ->orderBy('id')
                ->get(),
        ];
    }

    /**
     * @return array{
     *     bloodGroups: Collection<int, BloodGroup>,
     *     departments: Collection<int, Department>,
     *     designations: Collection<int, Designation>,
     *     employees: Collection<int, Employee>
     * }
     */
    public function getFormOptions(): array
    {
        return [
            'bloodGroups' => BloodGroup::query()->orderBy('name')->get(),
            'departments' => Department::query()->where('status', 1)->orderBy('name')->get(),
            'designations' => Designation::query()->where('status', 1)->orderBy('name')->get(),
            'employees' => Employee::query()
                ->with('user:id,first_name,last_name')
                ->where('status', 1)
                ->orderBy('id')
                ->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createEmployee(array $data): Employee
    {
        $profilePhotoPath = null;
        $nidImagePath = null;

        try {
            $profilePhotoPath = updateImagePath($data['profile_photo_path'], null, 'employees/profile');
            $nidImagePath = updateImagePath($data['nid_image'], null, 'employees/nid', 'local');

            return DB::transaction(function () use ($data, $profilePhotoPath, $nidImagePath): Employee {
                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? null,
                    'username' => User::generateUsername($data['first_name'], (int) Carbon::parse($data['joining_date'])->year),
                    'email' => $data['email'],
                    'password' => '12345678',
                    'role' => 2,
                    'profile_photo_path' => $profilePhotoPath,
                ]);

                $employeeId = Carbon::parse($data['joining_date'])->format('Y')
                    .str_pad((string) $user->id, 5, '0', STR_PAD_LEFT);

                return Employee::create([
                    ...Arr::except($data, ['first_name', 'last_name', 'email', 'nid_image', 'profile_photo_path']),
                    'user_id' => $user->id,
                    'employee_id' => $employeeId,
                    'nid_image' => $nidImagePath,
                ]);
            });
        } catch (Throwable $exception) {
            if ($profilePhotoPath !== null) {
                $this->deleteProfilePhotoIfExists($profilePhotoPath);
            }

            if ($nidImagePath !== null) {
                $this->deletePrivateImageIfExists($nidImagePath);
            }

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateEmployee(Employee $employee, array $data): Employee
    {
        $newProfilePhotoPath = isset($data['profile_photo_path'])
            ? updateImagePath($data['profile_photo_path'], null, 'employees/profile')
            : null;
        $newNidImagePath = isset($data['nid_image'])
            ? updateImagePath($data['nid_image'], null, 'employees/nid', 'local')
            : null;
        $oldProfilePhotoPath = $employee->user->profile_photo_path;
        $oldNidImagePath = $employee->nid_image;

        try {
            DB::transaction(function () use ($employee, $data, $newProfilePhotoPath, $newNidImagePath): void {
                $employee->user->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? null,
                    'email' => $data['email'],
                ]);

                if ($newProfilePhotoPath !== null) {
                    $employee->user->forceFill(['profile_photo_path' => $newProfilePhotoPath])->save();
                }

                $employeeData = Arr::except($data, ['first_name', 'last_name', 'email', 'nid_image', 'profile_photo_path']);

                if ($newNidImagePath !== null) {
                    $employeeData['nid_image'] = $newNidImagePath;
                }

                $employee->update($employeeData);
            });
        } catch (Throwable $exception) {
            if ($newProfilePhotoPath !== null) {
                $this->deleteProfilePhotoIfExists($newProfilePhotoPath);
            }

            if ($newNidImagePath !== null) {
                $this->deletePrivateImageIfExists($newNidImagePath);
            }

            throw $exception;
        }

        if ($newProfilePhotoPath !== null && $oldProfilePhotoPath !== null) {
            $this->deleteProfilePhotoIfExists($oldProfilePhotoPath);
        }

        if ($newNidImagePath !== null && $oldNidImagePath !== null) {
            $this->deletePrivateImageIfExists($oldNidImagePath);
        }

        return $employee->refresh();
    }

    public function deleteEmployee(Employee $employee): void
    {
        $user = $employee->user;
        $profilePhotoPath = $user->profile_photo_path;
        $nidImagePath = $employee->nid_image;

        DB::transaction(function () use ($employee, $user): void {
            $employee->delete();
            $user->delete();
        });

        if ($profilePhotoPath !== null) {
            $this->deleteProfilePhotoIfExists($profilePhotoPath);
        }

        if ($nidImagePath !== null) {
            $this->deletePrivateImageIfExists($nidImagePath);
        }
    }

    public function changeStatus(Employee $employee): Employee
    {
        $employee->update(['status' => ! $employee->getRawOriginal('status')]);

        return $employee->refresh();
    }

    public function updateShowAsTeam(Employee $employee, bool $showAsTeam): Employee
    {
        $employee->update(['show_as_team' => $showAsTeam]);

        return $employee->refresh();
    }

    private function deletePrivateImageIfExists(string $path): void
    {
        $disk = Storage::disk('local');

        if ($disk->exists($path) && ! $disk->delete($path)) {
            throw new RuntimeException('Unable to remove the employee NID image.');
        }
    }

    private function deleteProfilePhotoIfExists(string $path): void
    {
        if (! Str::startsWith($path, ['uploads/user/profile/', 'uploads/employees/profile/'])) {
            return;
        }

        $absolutePath = public_path($path);

        if (is_file($absolutePath) && ! unlink($absolutePath)) {
            throw new RuntimeException('Unable to remove the employee profile image.');
        }
    }
}
