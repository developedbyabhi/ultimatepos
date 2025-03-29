<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Business;

class AssignProductVerifyPermissionToAdmins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get the "product.verify" permission; create if it does not exist
        $permission = Permission::where('name', 'product.verify')->first();
        if (! $permission) {
            $permission = Permission::create(['name' => 'product.verify']);
        }

        // Get all businesses and assign the permission to each admin role
        $businesses = Business::all();
        foreach ($businesses as $business) {
            // The admin role for each business is named "Admin#<business_id>"
            $adminRole = Role::where('name', 'Admin#' . $business->id)->first();
            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Get all businesses and revoke the permission from each admin role
        $businesses = Business::all();
        foreach ($businesses as $business) {
            $adminRole = Role::where('name', 'Admin#' . $business->id)->first();
            if ($adminRole) {
                $adminRole->revokePermissionTo('product.verify');
            }
        }
    }
}