<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classes')->insert([
                    [
                'class' => '4',
                'whatsapp_group_id' => '120363422000129432@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],
            [
                'class' => '6',
                'whatsapp_group_id' => '120363421687565349@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],
            [
                'class' => '5',
                'whatsapp_group_id' => '120363403461400818@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],
            [
                'class' => '1',
                'whatsapp_group_id' => '120363422743525022@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],
            [
                'class' => '3',
                'whatsapp_group_id' => '120363421345743292@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],
            [
                'class' => '2',
                'whatsapp_group_id' => '120363422319084822@g.us',
                'whatsapp_group_link' => null, // Kosongkan jika tidak ada
            ],

        ]);
    }
}
