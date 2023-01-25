<?php

use Illuminate\Database\Seeder;
use App\Models\RoleHasPermissions;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoriesTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   
   public function run()
   {
   	    DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
        
        $data = array(
        	[
        		'name'=> 'Sketch',
        	],
        	[
        		'name'=> 'Canvas',
        	],
        	[
        		'name'=> 'Oil Paint',
          ],
        );
        
        Category::insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
   }
}