<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Visibility;
use Illuminate\Support\Facades\DB;

class VisibilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('visibilities')->insert([
            'id'      => '1',
            'name'     => 'public',
        ]);
        DB::table('visibilities')->insert([
            'id'      => '2',
            'name'     => 'contacts',
        ]);
        DB::table('visibilities')->insert([
            'id'      => '3',
            'name'     => 'private',
        ]);
    }
}
