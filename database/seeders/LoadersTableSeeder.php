<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoadersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('loaders')->insert([
            'name' => 'Generic Text',
            'description' => 'Generic Text Loader to load text from URL or file',
            'className' => 'GenericText',
            'options' => json_encode(['headers'=>['User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/117.0']]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

    }
}
