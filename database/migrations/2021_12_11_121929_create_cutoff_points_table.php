<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCutoffPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutoff_points', function (Blueprint $table) {
            $table->id();
            $table->decimal('male_points');
            $table->decimal('female_points');
            $table->decimal('average_points');
            $table->string('year');
            $table->string('intake_name');
            $table->enum('scheme',['private','government'])->default('private');
            $table->foreignId('course_id')->nullable(false);
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutoff_points');
    }
}
