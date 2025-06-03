<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();   //here we are disabling it so that if there are any parent/child relations
        // with other tables, it should not stop us from doing what we want or prompt any errors.

        if(Schema::hasTable('permissions')){
            DB::table('permissions')->truncate();
        }
        // this function here makes sure that after running the seeder duplication of data is ignored so if there is data
        // already in the table, first it will drop/empty the table then store latest permissions

        Schema::enableForeignKeyConstraints();

        // again enabling to maintain all relations (parent/child) will all other tables to ensure the smooth functioning

        $permissions = $this->getPermissions();   // it is a php function from php.net which returns an array.

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }
    }
    /**
     * Get the permissions data from an external source (e.g., array, configuration file).
     */
    private function getPermissions()
    {
        return [

            [
                'name' => 'userAdd',
                'description' => 'This will allow to add the user',
                'category' => 'user',

            ],
            [
                'name' => 'userView',
                'description' => 'This will allow to view the user',
                'category' => 'user',

            ],
            [
                'name' => 'userDelete',
                'description' => 'This will allow to delete the user',
                'category' => 'user',

            ],
            [
                'name' => 'userUpdate',
                'description' => 'This will allow to update the user',
                'category' => 'user',

            ],
            [
                'name' => 'roleView',
                'description' => 'This will allow to view the roles',
                'category' => 'role',

            ],
            [
                'name' => 'roleAdd',
                'description' => 'This will allow to add the roles',
                'category' => 'role',

            ],
            [
                'name' => 'roleUpdate',
                'description' => 'This will allow to update the roles',
                'category' => 'role',

            ],
            [
                'name' => 'roleDelete',
                'description' => 'This will allow to delete the roles',
                'category' => 'role',

            ],
       ];
 }
}