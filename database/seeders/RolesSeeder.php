<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $adminRole = Role::create(['name' => 'admin']);
        $eventManagerRole = Role::create(['name' => 'eventManager']);
        $exposantRole = Role::create(['name' => 'exposant']);


        Permission::create(['name' => 'showAllEventManagers']);
        Permission::create(['name' => 'readEventManager']);
        
        $adminRole->givePermissionTo([
            'showAllEventManagers',
        ]);

        $eventManagerRole->givePermissionTo([
            'readEventManager',
        ]);
    }
}
