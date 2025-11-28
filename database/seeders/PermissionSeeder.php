<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions by module
        $permissions = [
            'users' => [
                'view-users', 'create-users', 'edit-users', 'delete-users',
                'manage-user-roles', 'view-user-profile', 'export-users'
            ],
            'roles' => [
                'view-roles', 'create-roles', 'edit-roles', 'delete-roles', 'manage-permissions'
            ],
            'projects' => [
                'view-projects', 'create-projects', 'edit-projects', 'delete-projects',
                'manage-project-members', 'view-project-reports', 'export-projects'
            ],
            'attendance' => [
                'view-attendance', 'mark-attendance', 'edit-attendance',
                'approve-attendance', 'view-attendance-reports', 'export-attendance'
            ],
            'leaves' => [
                'view-leaves', 'apply-leave', 'approve-leaves',
                'reject-leaves', 'view-leave-reports', 'export-leaves'
            ],
            'reports' => [
                'view-dashboard', 'view-analytics', 'generate-reports',
                'export-reports', 'view-system-logs'
            ],
            'settings' => [
                'view-settings', 'edit-settings', 'backup-system',
                'view-activity-logs', 'manage-system'
            ]
        ];

        // Create permissions
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $permission) {
                try {
                    Permission::create([
                        'name' => $permission,
                        'guard_name' => 'web'
                    ]);
                    $this->command->info("Created permission: {$permission}");
                } catch (\Exception $e) {
                    $this->command->warn("Permission {$permission} might already exist: " . $e->getMessage());
                }
            }
        }

        $this->command->info('Permissions seeding completed successfully!');
    }
}