<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles with their permissions
        $roles = [
            'super-admin' => [
                'description' => 'Super Administrator with full system access',
                'permissions' => 'all'
            ],
            'admin' => [
                'description' => 'Administrator with management access',
                'permissions' => [
                    'view-users', 'create-users', 'edit-users', 'manage-user-roles', 'export-users',
                    'view-roles', 'create-roles', 'edit-roles',
                    'view-projects', 'create-projects', 'edit-projects', 'manage-project-members', 'export-projects',
                    'view-attendance', 'edit-attendance', 'approve-attendance', 'view-attendance-reports', 'export-attendance',
                    'view-leaves', 'approve-leaves', 'reject-leaves', 'view-leave-reports', 'export-leaves',
                    'view-dashboard', 'view-analytics', 'generate-reports', 'export-reports',
                    'view-settings', 'edit-settings', 'view-activity-logs'
                ]
            ],
            'project-manager' => [
                'description' => 'Project Manager with project and team management access',
                'permissions' => [
                    'view-users', 'view-user-profile',
                    'view-projects', 'create-projects', 'edit-projects', 'manage-project-members', 'view-project-reports',
                    'view-attendance', 'approve-attendance', 'view-attendance-reports',
                    'view-leaves', 'approve-leaves', 'reject-leaves', 'view-leave-reports',
                    'view-dashboard', 'view-analytics', 'generate-reports'
                ]
            ],
            'employee' => [
                'description' => 'Regular Employee with basic access',
                'permissions' => [
                    'view-user-profile',
                    'view-projects',
                    'mark-attendance', 'view-attendance',
                    'apply-leave', 'view-leaves',
                    'view-dashboard'
                ]
            ],
            'consultant' => [
                'description' => 'External Consultant with limited project access',
                'permissions' => [
                    'view-user-profile',
                    'view-projects',
                    'mark-attendance', 'view-attendance',
                    'apply-leave', 'view-leaves'
                ]
            ]
        ];

        foreach ($roles as $roleName => $roleData) {
            try {
                $role = Role::create([
                    'name' => $roleName,
                    'guard_name' => 'web'
                ]);

                // Assign permissions
                if ($roleData['permissions'] === 'all') {
                    $permissions = Permission::all();
                    $role->syncPermissions($permissions);
                } else {
                    $permissions = Permission::whereIn('name', $roleData['permissions'])->get();
                    $role->syncPermissions($permissions);
                }

                $this->command->info("Role '{$roleName}' created with permissions!");
            } catch (\Exception $e) {
                $this->command->warn("Role {$roleName} might already exist: " . $e->getMessage());
            }
        }
    }
}