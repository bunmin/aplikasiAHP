<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJumlahNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jumlah_nilais', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('topik_id');
            $table->integer('kriteria_id')->nullable();
            $table->integer('alternatif_id')->nullable();
            $table->float('jumlah_nilai');
            $table->float('rata_rata_nilai');
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
        Schema::dropIfExists('jumlah_nilais');
    }
}
