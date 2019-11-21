<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeJumlahNilaiKriteria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE jumlah_nilai_kriterias MODIFY jumlah_nilai double(8,3) ');
        DB::statement('ALTER TABLE jumlah_nilai_kriterias MODIFY rata_rata_nilai double(8,3) ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
