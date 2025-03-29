<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SellVerifyPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create the 'sell.verify' permission if it does not exist
        $permission = Permission::firstOrCreate(['name' => 'sell.verify']);

        // Retrieve all roles containing 'admin' in their name
        $adminRoles = Role::where('name', 'like', '%admin%')->get();

        // Assign the 'sell.verify' permission to each admin role
        foreach ($adminRoles as $role) {
            $role->givePermissionTo($permission);
        }

        // Optionally output a success message
        if (isset($this->command)) {
            $this->command->info('Sell verify permission assigned to all admin roles successfully.');
        }
    }
}
