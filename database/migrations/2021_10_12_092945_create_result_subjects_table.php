<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('result_subjects', function (Blueprint $table) {
            $table->id();
            $table->enum('level',['O','A']);
            $table->enum('grade',['A','B','C','D','E','F','O','U','P']);	
            $table->decimal('score');
            $table->foreignId('subject_id')->nullable(true);
            $table->foreignId('result_id')->nullable(false);
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('result_id')->references('id')->on('subjects')->cascadeOnDelete();
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
        Schema::dropIfExists('result_subjects');
    }
}
