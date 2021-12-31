<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnershipColumnToUniversitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->enum('ownership',['private','public'])->default('private');
            $table->string('portal_url')->nullable();
            $table->string('zip')->nullable();
            $table->integer('no_of_campuses')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->dropColumn('ownership');
            $table->dropColumn('portal_url');
            $table->dropColumn('no_of_campuses');
            $table->dropColumn('zip');
        });
    }
}
