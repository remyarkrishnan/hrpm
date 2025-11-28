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
        Schema::table('users', function (Blueprint $table) {
            // Add new employee fields
            $table->enum('employee_type', ['daily', 'contract', 'permanent'])->default('permanent')->after('status');
            $table->string('photo')->nullable()->after('profile_image');

            // Bank Account Details
            $table->string('bank_name')->nullable()->after('bank_account');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('branch_name')->nullable()->after('ifsc_code');
            $table->string('bank_proof_document')->nullable()->after('branch_name');

            // Additional fields if needed
            $table->decimal('monthly_salary', 10, 2)->nullable()->after('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_type',
                'photo',
                'bank_name',
                'account_number',
                'ifsc_code',
                'branch_name',
                'bank_proof_document',
                'monthly_salary'
            ]);
        });
    }
};