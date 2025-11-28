<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get company details from config
        $companyDomain = env('COMPANY_DOMAIN', 'teqinvalley.in');
        $companyName = env('COMPANY_NAME', 'Teqin Vally');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin@123456');

        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => env('SUPER_ADMIN_EMAIL', "superadmin@{$companyDomain}"),
            'password' => Hash::make($adminPassword),
            'employee_id' => 'SA001',
            'department' => 'Administration',
            'designation' => 'Super Administrator',
            'joining_date' => now(),
            'status' => true,
            'phone' => '+91 9876543210',
            'email_verified_at' => now()
        ]);
        $superAdmin->assignRole('super-admin');

        // Create Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => env('ADMIN_EMAIL', "admin@{$companyDomain}"),
            'password' => Hash::make($adminPassword),
            'employee_id' => 'AD001',
            'department' => 'Administration',
            'designation' => 'Administrator',
            'joining_date' => now(),
            'status' => true,
            'phone' => '+91 9876543211',
            'email_verified_at' => now()
        ]);
        $admin->assignRole('admin');

        // Create Project Manager
        $projectManager = User::create([
            'name' => 'John Manager',
            'email' => "manager@{$companyDomain}",
            'password' => Hash::make($adminPassword),
            'employee_id' => 'PM001',
            'department' => 'Project Management',
            'designation' => 'Senior Project Manager',
            'joining_date' => now()->subMonths(6),
            'status' => true,
            'phone' => '+91 9876543212',
            'email_verified_at' => now(),
            'salary' => 75000.00
        ]);
        $projectManager->assignRole('project-manager');

        // Create Employee
        $employee = User::create([
            'name' => 'Jane Employee',
            'email' => "employee@{$companyDomain}",
            'password' => Hash::make($adminPassword),
            'employee_id' => 'EMP001',
            'department' => 'Engineering',
            'designation' => 'Senior Engineer',
            'joining_date' => now()->subYear(),
            'status' => true,
            'phone' => '+91 9876543213',
            'email_verified_at' => now(),
            'salary' => 50000.00,
            'date_of_birth' => '1990-05-15',
            'gender' => 'female',
            'address' => '123 Main Street, City, State 12345'
        ]);
        $employee->assignRole('employee');

        // Create Consultant
        $consultant = User::create([
            'name' => 'Mike Consultant',
            'email' => "consultant@{$companyDomain}",
            'password' => Hash::make($adminPassword),
            'employee_id' => 'CON001',
            'department' => 'Consulting',
            'designation' => 'Senior Consultant',
            'joining_date' => now()->subMonths(3),
            'status' => true,
            'phone' => '+91 9876543214',
            'email_verified_at' => now(),
            'salary' => 60000.00
        ]);
        $consultant->assignRole('consultant');

        $this->command->info("Default users created successfully for {$companyName}!");
        $this->command->info("All emails use domain: {$companyDomain}");
        $this->command->info("Password for all accounts: {$adminPassword}");
    }
}