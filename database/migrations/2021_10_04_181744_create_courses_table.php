<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('code')->nullable(false)->unique();
            $table->enum('type',['DAY','AFTERNOON','EVENING','EXTERNAL','EXECUTIVE'])->nullable(true);
            $table->string('tag')->nullable(true);
            $table->integer('years')->nullable(false)->default(3);
            $table->integer('tuition_fees')->nullable(false)->default(0);
            $table->foreignId('university_id')->nullable(false);
            $table->foreignId('campus_id')->nullable(true);
            $table->json('essential_subjects')->nullable(false);
            $table->json('relevant_subjects')->nullable(false);
            $table->json('desirable_subjects')->nullable(false);
            $table->foreign('university_id')->references('id')->on('universities')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('campus_id')->references('id')->on('campuses')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
