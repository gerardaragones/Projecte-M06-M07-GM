<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::insert('insert into roles (id, name) values (?, ?)', [1, 'author']);
        DB::insert('insert into roles (id, name) values (?, ?)', [2, 'editor']);
        DB::insert('insert into roles (id, name) values (?, ?)', [3, 'admin']);

    }
}
