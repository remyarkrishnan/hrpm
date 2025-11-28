<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_manager', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->date('assigned_date')->default(now());
            $table->date('end_date')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index(['employee_id', 'active']);
            $table->index(['manager_id', 'active']);

            // Ensure employee cannot be their own manager
            $table->unique(['employee_id', 'manager_id', 'active'], 'unique_active_employee_manager');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_employee_manager');
    }
};