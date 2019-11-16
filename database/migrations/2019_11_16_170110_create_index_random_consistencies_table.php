<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndexRandomConsistenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('index_random_consistencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ukuran_metrix')->nullable();
            $table->float('nilai')->nullable();
            $table->timestamps();
        });


         // Insert some stuff
        DB::table('index_random_consistencies')->insert(
            array([
                'ukuran_metrix' => '1',
                'nilai' => '0.00'
            ],[
                'ukuran_metrix' => '2',
                'nilai' => '0.00'
            ],[
                'ukuran_metrix' => '3',
                'nilai' => '0.58'
            ],[
                'ukuran_metrix' => '4',
                'nilai' => '0.90'
            ],[
                'ukuran_metrix' => '5',
                'nilai' => '1.12'
            ],[
                'ukuran_metrix' => '6',
                'nilai' => '1.24'
            ],[
                'ukuran_metrix' => '7',
                'nilai' => '1.32'
            ],[
                'ukuran_metrix' => '8',
                'nilai' => '1.41'
            ],[
                'ukuran_metrix' => '9',
                'nilai' => '1.45'
            ],[
                'ukuran_metrix' => '10',
                'nilai' => '1.49'
            ],[
                'ukuran_metrix' => '11',
                'nilai' => '1.51'
            ],[
                'ukuran_metrix' => '12',
                'nilai' => '1.48'
            ],[
                'ukuran_metrix' => '13',
                'nilai' => '1.56'
            ],[
                'ukuran_metrix' => '14',
                'nilai' => '1.57'
            ],[
                'ukuran_metrix' => '15',
                'nilai' => '1.59'
            ])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('index_random_consistencies');
    }
}
