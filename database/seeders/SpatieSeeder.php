<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Reset cached roles and permissions
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

         // create permissions
         Permission::create(['name' => 'create user']);
         Permission::create(['name' => 'read user']);
         Permission::create(['name' => 'update user']);
         Permission::create(['name' => 'delete user']);
 
         Permission::create(['name' => 'create transaction']);
         Permission::create(['name' => 'read transaction']);
         Permission::create(['name' => 'update transaction']);
         Permission::create(['name' => 'delete transaction']);
         Permission::create(['name' => 'aprove transaction']);
 
         // create roles and assign created permissions
         $superAdmin = Role::create(['name' => 'super-admin']);
         $superAdmin->givePermissionTo(Permission::all());
 
         $admin = Role::create(['name' => 'admin']);
         $admin->givePermissionTo('read user');
         $admin->givePermissionTo('create user');
         $admin->givePermissionTo('read transaction');
         $admin->givePermissionTo('create transaction');
 
         $agent = Role::create(['name' => 'agent']);
         $agent->givePermissionTo('read transaction');
         $agent->givePermissionTo('create transaction');

         $operator = Role::create(['name' => 'operator']);
         $operator->givePermissionTo('aprove transaction');

         $envoy = Role::create(['name' => 'envoy']);
         $envoy->givePermissionTo('aprove transaction');
    }
}