<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('short_name')->nullable(true);
            $table->string('description')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('phone')->nullable(true);
            $table->string('fax')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('website')->nullable(true);
            $table->string('logo')->nullable(true);
            $table->string('cover_image')->nullable(true);
            $table->string('facebook')->nullable(true);
            $table->string('twitter')->nullable(true);
            $table->string('instagram')->nullable(true);
            $table->string('youtube')->nullable(true);
            $table->string('linkedin')->nullable(true);
            $table->string('mission')->nullable(true);
            $table->string('vision')->nullable(true);
            $table->string('motto')->nullable(true);
            $table->string('affiliation')->nullable(true);
            $table->date('accreditation_date')->nullable(true);
            $table->string('accreditation_expiration')->nullable(true);
            $table->string('accreditation_authority')->nullable(true);

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
        Schema::dropIfExists('universities');
    }
}
