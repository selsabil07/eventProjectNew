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
            'first_name' => 'admin_first_name',
            'last_name' => 'admin_last_name',
            'birthday' => '2000-01-01',
            'email' => 'admin@gmail.com',
            'organization' => 'adminOrg',
            'phone' => '0505050505',
            'password' => bcrypt('12345'),
        ]);
        $user->assignRole('admin');
    }
}
