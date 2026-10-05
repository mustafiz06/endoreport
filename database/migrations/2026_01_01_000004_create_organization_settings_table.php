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
        Schema::create('organization_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Apex Endoscopy & Diagnostic Center');
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('license_number')->nullable();
            
            // Report settings
            $table->text('report_header')->nullable();
            $table->text('report_footer')->nullable();
            $table->boolean('doctor_signature')->default(true);
            $table->string('stamp')->nullable();
            $table->string('default_report_font')->default('Arial');
            $table->string('default_report_format')->default('A4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_settings');
    }
};
