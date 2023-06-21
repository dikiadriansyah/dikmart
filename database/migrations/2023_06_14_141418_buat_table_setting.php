<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BuatTableSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('setting', function (Blueprint $table) {
            $table->increments('id_setting');
            $table->string('nama_perusahaan', 100);
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->string('logo', 50);
            $table->string('kartu_member');
            $table->integer('diskon_member')->unsigned();
            $table->integer('tipe_nota')->unsigned();
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
        //
    }
}
