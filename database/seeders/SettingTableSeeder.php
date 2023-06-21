<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('setting')->insert(array(
            [
                'nama_perusahaan' => 'Dikmart',
                'alamat' => 'Jl. Moh. Kahfi 2',
                'telepon' => '089616023080',
                'logo' => 'logo.png',
                'kartu_member' => 'card.png',
                'diskon_member' => '10',
                'tipe_nota' => '0'
            ]
        ));
    }
}
