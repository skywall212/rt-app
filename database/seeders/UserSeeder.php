<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin RW',
                'email' => 'admin@rw.com',
                'role' => 'admin',
                'nohp' => '081234567890',
                'password' => Hash::make('123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Viewer RW',
                'email' => 'viewer@rw.com',
                'role' => 'viewer',
                'nohp' => '089876543210',
                'password' => Hash::make('123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
