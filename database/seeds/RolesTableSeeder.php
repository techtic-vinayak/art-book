<?php
use Illuminate\Database\Seeder;
use App\Models\RoleHasPermissions;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
class RolesTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   protected $toTruncate = ['model_has_permissions','model_has_roles','role_has_permissions','permissions','roles'];
   public function run()
   {
   		DB::statement('SET FOREIGN_KEY_CHECKS=0');
   		foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }
        $data = array(
        	[
        		'name'=> 'Super Admin',
        		'guard_name'=> 'super-admin',
        	],
        	[
        		'name'=> 'Admin',
        		'guard_name'=> 'admin',
        	],
        	[
        		'name'=> 'Users',
        		'guard_name'=> 'users',
        	],
        );
        Role::insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
   }
}