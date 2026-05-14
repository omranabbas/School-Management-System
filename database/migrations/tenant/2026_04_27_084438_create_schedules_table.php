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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_subjects_id')
                ->constrained('teacher_subjects')
                ->cascadeOnDelete();
            $table->enum('day', [
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday'
            ]);
            $table->integer('period');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            $table->unique(['teacher_subjects_id', 'day', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
