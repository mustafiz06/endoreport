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
        Schema::create('captured_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('examination_id')->constrained('examinations')->restrictOnDelete();
            $table->string('filename');
            $table->string('path');
            $table->timestamp('captured_at')->useCurrent();
            $table->foreignId('captured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('sequence')->default(1);
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->index(['examination_id', 'sequence']);
            $table->index('patient_id');
        });

        Schema::create('recorded_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('examination_id')->constrained('examinations')->restrictOnDelete();
            $table->string('filename');
            $table->string('path');
            $table->string('duration')->nullable(); // e.g. "02:45"
            $table->string('resolution')->nullable(); // e.g. "1080p", "4K"
            $table->timestamp('recorded_at')->useCurrent();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('examination_id');
            $table->index('patient_id');
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->restrictOnDelete();
            $table->foreignId('examination_id')->unique()->constrained('examinations')->restrictOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->restrictOnDelete();
            $table->unsignedBigInteger('report_template_id')->nullable();
            $table->longText('findings')->nullable();
            $table->text('impression')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', ['Draft', 'Finalized'])->default('Draft')->index();
            $table->timestamp('finalized_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('recorded_videos');
        Schema::dropIfExists('captured_images');
    }
};
