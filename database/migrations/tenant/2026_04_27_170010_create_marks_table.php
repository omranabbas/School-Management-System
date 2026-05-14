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
        Schema::create('marks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('enrollment_id')
          ->constrained('student_enrollments')
          ->cascadeOnDelete();
    $table->foreignId('teacher_subjects_id')
          ->constrained('teacher_subjects')
          ->restrictOnDelete();
    $table->decimal('score', 8, 2);
    $table->decimal('max_score', 8, 2);
    $table->integer('term');
    $table->enum('type', ['midterm', 'final']);
    $table->date('exam_date');
    $table->timestamps();
    $table->unique([
        'enrollment_id',
        'teacher_subjects_id',
        'term',
        'type'
    ]);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks');
    }
};
