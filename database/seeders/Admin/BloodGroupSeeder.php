<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\BloodGroup;
use Illuminate\Database\Seeder;

class BloodGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodGroups = [
            ['name' => 'A+'],
            ['name' => 'A-'],
            ['name' => 'B+'],
            ['name' => 'B-'],
            ['name' => 'AB+'],
            ['name' => 'AB-'],
            ['name' => 'O+'],
            ['name' => 'O-'],
        ];

        foreach ($bloodGroups as $bloodGroup) {
            BloodGroup::query()->updateOrCreate(
                ['name' => $bloodGroup['name']],
                $bloodGroup,
            );
        }
    }
}
