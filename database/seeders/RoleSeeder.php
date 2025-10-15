<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{

    public function run()
    {
        Schema::disableForeignKeyConstraints();   //here we are disabling it so that if there are any parent/child relations
        // with other tables, it should not stop us from doing what we want or prompt any errors.

        if (Schema::hasTable('roles')) {
            DB::table('roles')->truncate();
        }
        // this function here makes sure that after running the seeder duplication of data is ignored so if there is data
        // already in the table, first it will drop/empty the table then store latest permissions

        Schema::enableForeignKeyConstraints();

        // again enabling to maintain all relations (parent/child) will all other tables to ensure the smooth functioning

        $roles = $this->getRoles();  // it is a php function from php.net which returns an array.

        foreach ($roles as $role) {
            $permissionIds = implode(',', $role['permission_id']);
            // fetching array from below, imploding it/converting it into string and assigning to $permissionIds

            $role['permission_id'] = $permissionIds;
            // assigning the converted string/updated the original arrays data type as string and value.

            Role::firstOrCreate($role);
        }
    }

    /**
     * Get the roles data from an external source (e.g., array, configuration file).
     */

    private function getRoles()
    {
        // Alternatively, you can define the roles directly in the seeder:
        return [
            [
                'title' => 'Super Admin',
                'permission_id' => [1, 2, 3, 4, 11, 12, 13, 14, 15, 16, 18, 22, 23], // User Listing, Instructor Listing, Pricing, Course Management, Meta Tags, My Courses, Add New Courses, Community Settings, Marketing
            ],
            [
                'title' => 'Admin', // Instructor
                'permission_id' => [14, 15, 18, 21], // My Courses, Add New Courses, Community, Help
            ],
            [
                'title' => 'User', // Student
                'permission_id' => [16, 17, 18, 19, 20, 21], // Dashboard, My Career Journey, Community, My Library, Content, Help
            ]
        ];
    }
}