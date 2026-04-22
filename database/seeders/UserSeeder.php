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
        
        UserFactory::new()->count(10)->create();
    }
}
