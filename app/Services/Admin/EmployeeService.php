<?php

namespace App\Services\Admin;

use App\Models\Admin\BloodGroup;
use App\Models\Admin\Department;
use App\Models\Admin\Designation;
use App\Models\Admin\Employee;
use App\Models\User;
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
    public function getAllEmployees(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return Employee::query()
            ->with([
                'user:id,name,email,profile_photo_path',
                'bloodGroup:id,name',
                'department:id,name',
                'designation:id,name',
            ])
            ->when($search !== null && $search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('phone', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function (Builder $query) use ($search): void {
                            $query->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });
                });
            })
            ->when(in_array($status, ['0', '1'], true), fn (Builder $query): Builder => $query->where('status', (int) $status))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
    }

    /**
     * @return array{
     *     bloodGroups: Collection<int, BloodGroup>,
     *     departments: Collection<int, Department>,
     *     designations: Collection<int, Designation>
     * }
     */
    public function getFormOptions(): array
    {
        return [
            'bloodGroups' => BloodGroup::query()->orderBy('name')->get(),
            'departments' => Department::query()->where('status', 1)->orderBy('name')->get(),
            'designations' => Designation::query()->where('status', 1)->orderBy('name')->get(),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createEmployee(array $data): Employee
    {
        $profilePhotoPath = updateImagePath($data['profile_photo_path'], null, 'user/profile');
        $nidImagePath = null;

        try {
            return DB::transaction(function () use ($data, $nidImagePath): Employee {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => '12345678',
                    'role' => 2,
                    'profile_photo_path' => $profilePhotoPath,
                ]);


                return Employee::create([
                    ...Arr::except($data, ['name', 'email', 'nid_image', 'profile_photo_path']),
                    'user_id' => $user->id,
                    'nid_image' => $nidImagePath,
                ]);
            });
        } catch (Throwable $exception) {
            $this->deleteProfilePhotoIfExists($profilePhotoPath);

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
            ? updateImagePath($data['profile_photo_path'], null, 'user/profile')
            : null;
        $newNidImagePath = isset($data['nid_image'])
            ? updateImagePath($data['nid_image'], null, 'employees/nid', 'local')
            : null;
        $oldProfilePhotoPath = $employee->user->profile_photo_path;
        $oldNidImagePath = $employee->nid_image;

        try {
            DB::transaction(function () use ($employee, $data, $newProfilePhotoPath, $newNidImagePath): void {
                $employee->user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);

                if ($newProfilePhotoPath !== null) {
                    $employee->user->forceFill(['profile_photo_path' => $newProfilePhotoPath])->save();
                }

                $employeeData = Arr::except($data, ['name', 'email', 'nid_image', 'profile_photo_path']);

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
        if (! Str::startsWith($path, 'uploads/user/profile/')) {
            return;
        }

        $absolutePath = public_path($path);

        if (is_file($absolutePath) && ! unlink($absolutePath)) {
            throw new RuntimeException('Unable to remove the employee profile image.');
        }
    }
}
