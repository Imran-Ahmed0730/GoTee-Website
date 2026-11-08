<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Banner
            'Banner Add',
            'Banner Delete',
            'Banner Status Change',
            'Banner Update',
            'Banner View',

            // Blog Category
            'Blog Category Add',
            'Blog Category Delete',
            'Blog Category Status Change',
            'Blog Category Update',
            'Blog Category View',
            'Blog Category Details',

            // Blog
            'Blog Add',
            'Blog Delete',
            'Blog Status Change',
            'Blog Update',
            'Blog View',
            'Blog Details',

            // FAQ Category
            'FAQ Category Add',
            'FAQ Category Delete',
            'FAQ Category Status Change',
            'FAQ Category Update',
            'FAQ Category View',
            'FAQ Category Details',
            // FAQ
            'FAQ Add',
            'FAQ Delete',
            'FAQ Status Change',
            'FAQ Update',
            'FAQ View',

            // Client
            'Client Add',
            'Client Delete',
            'Client Status Change',
            'Client Update',
            'Client View',

            // Testimonials
            'Testimonial Add',
            'Testimonial Delete',
            'Testimonial Status Change',
            'Testimonial Update',
            'Testimonial View',

            // Case Studies
            'Case Study Add',
            'Case Study Delete',
            'Case Study Status Change',
            'Case Study Update',
            'Case Study View',

            // Employee
            'Employee Add',
            'Employee Delete',
            'Employee Status Change',
            'Employee Update',
            'Employee View',

            // Permission
            'Permission Add',
            'Permission Delete',
            'Permission Status Change',
            'Permission Update',
            'Permission View',

            // Role
            'Role Add',
            'Role Assignment',
            'Role Delete',
            'Role Permission Add/Update',
            'Role Update',
            'Role View',

            // Settings
            'Settings Contact',
            'Settings Logo & Favicon',
            'Settings Recaptcha',
            'Settings Site',
            'Settings Social Media',
            'Settings Smtp',
            'Settings Update',
            'Settings View',

            // Staff
            'Staff Create',
            'Staff Delete',
            'Staff Status Change',
            'Staff Update',
            'Staff View',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
