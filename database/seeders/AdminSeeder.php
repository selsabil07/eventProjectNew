<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'user_name' => 'admin',
            'first_name' => 'admin_first_name',
            'last_name' => 'admin_last_name',
            'email' => 'admin@gmail.com',
            'organization' => 'adminOrg',
            'phone' => '0505050505',
            'password' => bcrypt('12345'),
            'approved' => '1'
        ]);
        $user->assignRole('admin');
    }
}
