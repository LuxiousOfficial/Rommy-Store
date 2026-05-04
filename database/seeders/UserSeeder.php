<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Rommy Ardiansyah',
            'email' => 'rommyardiansyah009@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('vizleon1999'),
        ])->assignRole('admin');

        $store = User::create([
            'name' => 'Rommy Ardiansyah',
            'email' => 'rommyardiansyah1@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('store');

        $buyer = User::create([
            'name' => 'Rommy Ardiansyah',
            'email' => 'rommyardiansyah2@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ])->assignRole('buyer');
        
        UserFactory::new()->count(10)->create();
    }
}
