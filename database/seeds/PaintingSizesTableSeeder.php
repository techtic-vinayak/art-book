<?php

use Illuminate\Database\Seeder;
use App\Models\RoleHasPermissions;
use App\Models\PaintingSize;
use Illuminate\Database\Eloquent\Model;

class PaintingSizesTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   
   public function run()
   {
   		DB::statement('SET FOREIGN_KEY_CHECKS=0');
   	  DB::table('panting_sizes')->truncate();
      
      $data = array(
        	[
        		'size'=> '4x4',
        	],
        	[
        		'size'=> '4x6',
        	],
        	[
        		'size'=> ' 8x10',
        	],
          [
            'size'=> '16x10',
          ],
        );
        
      PaintingSize::insert($data);
      DB::statement('SET FOREIGN_KEY_CHECKS=1');
   }
}