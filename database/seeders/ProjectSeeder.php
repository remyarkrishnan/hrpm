<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get company domain from config
        $companyDomain = env('COMPANY_DOMAIN', 'teqinvalley.in');

        // Get users for project assignments using the correct email addresses
        $superAdmin = User::where('email', env('SUPER_ADMIN_EMAIL', "superadmin@{$companyDomain}"))->first();
        $admin = User::where('email', env('ADMIN_EMAIL', "admin@{$companyDomain}"))->first();
        $manager = User::where('email', "manager@{$companyDomain}")->first();

        // Fallback to super admin if others don't exist
        $defaultManager = $manager ?: ($admin ?: $superAdmin);
        $defaultAdmin = $admin ?: $superAdmin;

        if (!$superAdmin) {
            $this->command->warn('No super admin found. Please check user seeding.');
            return;
        }

        // Create sample projects
        $projects = [
            [
                'name' => 'Office Complex Construction',
                'description' => 'Construction of a modern 5-story office complex with parking facilities and landscaping.',
                'client_name' => 'ABC Corporation',
                'client_contact' => '+91 9876543100',
                'manager_id' => $defaultManager->id,
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(9),
                'budget' => 50000000.00,
                'status' => 'active',
                'priority' => 'high',
                'completion_percentage' => 35,
                'location' => 'Mumbai',
                'address' => 'Plot No. 123, Sector 5, Navi Mumbai, Maharashtra 400614'
            ],
            [
                'name' => 'Residential Villa Project',
                'description' => 'Luxury residential villas with modern amenities and smart home features.',
                'client_name' => 'XYZ Developers',
                'client_contact' => '+91 9876543101',
                'manager_id' => $defaultManager->id,
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(10),
                'budget' => 75000000.00,
                'status' => 'active',
                'priority' => 'medium',
                'completion_percentage' => 20,
                'location' => 'Pune',
                'address' => 'Survey No. 456, Wakad, Pune, Maharashtra 411057'
            ],
            [
                'name' => 'Shopping Mall Renovation',
                'description' => 'Complete renovation of existing shopping mall including modernization of facilities.',
                'client_name' => 'Mall Management Ltd',
                'client_contact' => '+91 9876543102',
                'manager_id' => $defaultAdmin->id,
                'start_date' => Carbon::now()->subMonth(),
                'end_date' => Carbon::now()->addMonths(6),
                'budget' => 25000000.00,
                'status' => 'active',
                'priority' => 'urgent',
                'completion_percentage' => 10,
                'location' => 'Delhi',
                'address' => 'Connaught Place, New Delhi, Delhi 110001'
            ],
            [
                'name' => 'Infrastructure Development',
                'description' => 'Road construction and infrastructure development for new residential area.',
                'client_name' => 'Municipal Corporation',
                'client_contact' => '+91 9876543103',
                'manager_id' => $superAdmin->id,
                'start_date' => Carbon::now()->subWeek(),
                'end_date' => Carbon::now()->addMonths(4),
                'budget' => 15000000.00,
                'status' => 'planning',
                'priority' => 'medium',
                'completion_percentage' => 5,
                'location' => 'Bangalore',
                'address' => 'Electronic City Phase 2, Bangalore, Karnataka 560100'
            ],
            [
                'name' => 'Hospital Extension Project',
                'description' => 'Extension of existing hospital with new OPD block and parking facility.',
                'client_name' => 'City Hospital Trust',
                'client_contact' => '+91 9876543104',
                'manager_id' => $defaultManager->id,
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addMonths(8),
                'budget' => 35000000.00,
                'status' => 'planning',
                'priority' => 'high',
                'completion_percentage' => 0,
                'location' => 'Chennai',
                'address' => 'Anna Nagar, Chennai, Tamil Nadu 600040'
            ]
        ];

        foreach ($projects as $projectData) {
            try {
                $project = Project::create($projectData);
                $this->command->info("Created project: {$project->name}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to create project: " . $e->getMessage());
            }
        }

        $this->command->info('Sample projects created successfully for ' . env('COMPANY_NAME', 'Teqin Vally') . '!');
    }
}