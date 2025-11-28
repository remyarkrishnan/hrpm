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
        Schema::create('project_approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->integer('step_order');
            $table->string('step_name');
            $table->enum('consultancy_type', [
                'design', 'environment', 'safety', 'structural', 'electrical', 
                'plumbing', 'finance', 'legal', 'municipal', 'fire_safety', 
                'quality', 'final_approval'
            ]);
            $table->text('description')->nullable();
            $table->foreignId('responsible_person_id')->nullable()->constrained('users');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected', 'on_hold'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'step_order']);
            $table->index(['status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_approval_steps');
    }
};