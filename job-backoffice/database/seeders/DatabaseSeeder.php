<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the root admin

        User::create([
            'name'=>'Admin',
            'email'=>'admin@admin.com',
            'password'=>Hash::make('12345678'),
            'role'=>'admin',
            'email_verified_at'=>now(),
            
        ]);

        
    }
}
