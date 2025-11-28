<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get existing columns to avoid conflicts
        $columns = Schema::getColumnListing('projects');
        
        // First, let's see what status values exist and update them
        if (in_array('status', $columns)) {
            // Update any problematic status values to valid ones
            DB::statement("UPDATE projects SET status = 'draft' WHERE status IS NULL OR status = '' OR LENGTH(status) = 0");
            DB::statement("UPDATE projects SET status = 'draft' WHERE status NOT IN ('active', 'inactive', 'completed', 'pending')");
            DB::statement("UPDATE projects SET status = 'in_progress' WHERE status = 'active'");
            DB::statement("UPDATE projects SET status = 'completed' WHERE status = 'completed'");
            DB::statement("UPDATE projects SET status = 'draft' WHERE status = 'inactive'");
            DB::statement("UPDATE projects SET status = 'approval_pending' WHERE status = 'pending'");
        }
        
        // Add missing columns to projects table
        Schema::table('projects', function (Blueprint $table) use ($columns) {
            // Add project_code if it doesn't exist
            if (!in_array('project_code', $columns)) {
                $table->string('project_code')->nullable()->after('description');
            }
            
            // Add type column
            if (!in_array('type', $columns)) {
                $table->enum('type', ['residential', 'commercial', 'industrial', 'infrastructure', 'renovation'])
                      ->default('commercial')->after('project_code');
            }
            
            // Add location
            if (!in_array('location', $columns)) {
                $table->string('location')->default('Not Specified')->after('type');
            }
            
            // Add client information
            if (!in_array('client_name', $columns)) {
                $table->string('client_name')->default('Unknown Client')->after('location');
            }
            
            if (!in_array('client_contact', $columns)) {
                $table->string('client_contact')->nullable()->after('client_name');
            }
            
            // Add budget
            if (!in_array('budget', $columns)) {
                $table->decimal('budget', 15, 2)->default(100000)->after('client_contact');
            }
            
            // Add dates (keep them nullable for now)
            if (!in_array('start_date', $columns)) {
                $table->date('start_date')->nullable()->after('budget');
            }
            
            if (!in_array('expected_end_date', $columns)) {
                $table->date('expected_end_date')->nullable()->after('start_date');
            }
            
            if (!in_array('actual_end_date', $columns)) {
                $table->date('actual_end_date')->nullable()->after('expected_end_date');
            }
            
            // Add progress tracking
            if (!in_array('progress_percentage', $columns)) {
                $table->decimal('progress_percentage', 5, 2)->default(0)->after('status');
            }
            
            // Add relationships
            if (!in_array('project_manager_id', $columns)) {
                $table->unsignedBigInteger('project_manager_id')->nullable()->after('progress_percentage');
            }
            
            if (!in_array('created_by', $columns)) {
                $table->unsignedBigInteger('created_by')->nullable()->after('project_manager_id');
            }
            
            // Add priority
            if (!in_array('priority', $columns)) {
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])
                      ->default('medium')->after('created_by');
            }
            
            // Add documents
            if (!in_array('documents', $columns)) {
                $table->json('documents')->nullable()->after('priority');
            }
            
            // Add soft deletes
            if (!in_array('deleted_at', $columns)) {
                $table->softDeletes();
            }
        });
        
        // Update existing projects with default values
        DB::statement("
            UPDATE projects 
            SET 
                project_code = COALESCE(project_code, CONCAT('PROJ-', id, '-', YEAR(COALESCE(created_at, NOW())))),
                start_date = COALESCE(start_date, DATE(COALESCE(created_at, NOW()))),
                expected_end_date = COALESCE(expected_end_date, DATE_ADD(DATE(COALESCE(created_at, NOW())), INTERVAL 90 DAY))
            WHERE project_code IS NULL 
               OR start_date IS NULL 
               OR expected_end_date IS NULL
        ");
        
        // Now modify the status column to use the new enum values
        try {
            DB::statement("
                ALTER TABLE projects 
                MODIFY COLUMN status ENUM(
                    'draft', 'planning', 'approval_pending', 'approved', 
                    'in_progress', 'on_hold', 'completed', 'cancelled'
                ) DEFAULT 'draft'
            ");
        } catch (\Exception $e) {
            // If this fails, create a new status column
            Schema::table('projects', function (Blueprint $table) {
                $table->enum('new_status', [
                    'draft', 'planning', 'approval_pending', 'approved', 
                    'in_progress', 'on_hold', 'completed', 'cancelled'
                ])->default('draft')->after('actual_end_date');
            });
            
            // Copy values from old status to new status
            DB::statement("UPDATE projects SET new_status = status");
            
            // Drop old status column and rename new one
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            Schema::table('projects', function (Blueprint $table) {
                $table->renameColumn('new_status', 'status');
            });
        }
        
        // Make project_code unique (ignore if already exists)
        try {
            Schema::table('projects', function (Blueprint $table) {
                $table->unique('project_code');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
        
        // Add foreign key constraints (ignore if they already exist)
        try {
            Schema::table('projects', function (Blueprint $table) {
                $table->foreign('project_manager_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign keys might already exist
        }
        
        // Add indexes for performance
        try {
            Schema::table('projects', function (Blueprint $table) {
                $table->index(['status', 'created_at']);
                $table->index(['project_manager_id', 'status']);
            });
        } catch (\Exception $e) {
            // Indexes might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop foreign keys
            try {
                $table->dropForeign(['project_manager_id']);
                $table->dropForeign(['created_by']);
            } catch (\Exception $e) {
                // Ignore if they don't exist
            }
            
            // Drop indexes
            try {
                $table->dropIndex(['status', 'created_at']);
                $table->dropIndex(['project_manager_id', 'status']);
                $table->dropUnique(['project_code']);
            } catch (\Exception $e) {
                // Ignore if they don't exist
            }
            
            // Remove added columns
            $columnsToRemove = [
                'project_code', 'type', 'location', 'client_name', 'client_contact',
                'budget', 'start_date', 'expected_end_date', 'actual_end_date',
                'progress_percentage', 'project_manager_id', 'created_by', 
                'priority', 'documents', 'deleted_at'
            ];
            
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
