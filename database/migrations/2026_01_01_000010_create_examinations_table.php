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
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('examination_id')->unique()->index();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('procedure_id')->constrained('procedures')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->foreignId('referring_doctor_id')->nullable()->constrained('referring_doctors')->nullOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained('instruments')->nullOnDelete();
            $table->text('indication');
            $table->date('examination_date')->index();
            $table->time('examination_time');
            $table->text('notes')->nullable();
            $table->enum('status', [
                'Scheduled',
                'In Progress',
                'Captured',
                'Reporting',
                'Reviewed',
                'Finalized',
                'Cancelled'
            ])->default('Scheduled')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Compound/Covering indexes for performance
            $table->index(['patient_id', 'examination_date']);
            $table->index(['doctor_id', 'examination_date']);
            $table->index(['procedure_id', 'examination_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
