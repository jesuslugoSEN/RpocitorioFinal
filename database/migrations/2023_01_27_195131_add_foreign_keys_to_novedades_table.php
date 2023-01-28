<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->foreign(['id_elementos'], 'elementos')->references(['id'])->on('elementos');
            $table->foreign(['id_libros'], 'libros')->references(['id'])->on('libros');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->dropForeign('elementos');
            $table->dropForeign('libros');
        });
    }
};
