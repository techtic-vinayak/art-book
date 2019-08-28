<?php
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {

      DB::statement('SET FOREIGN_KEY_CHECKS=0');
      User::truncate();

      $admin = factory(App\Models\User::class)->create();
      $admin->name = "Admin";
      $admin->email = "admin@gmail.com";
      $admin->password = bcrypt("admin@123");
      $admin->save();
      $admin->roles()->sync(1);

      $admin = factory(App\Models\User::class)->create();
      $admin->name = "Test";
      $admin->email = "artist@yopmail.com";
      $admin->password = bcrypt("test@123");
      $admin->save();
      $admin->roles()->sync(2);

      $admin = factory(App\Models\User::class)->create();
      $admin->name = "art lover";
      $admin->email = "artlover@yopmail.com";
      $admin->password = bcrypt("test@123");
      $admin->save();
      $admin->roles()->sync(3);

      $users = factory(App\Models\User::class, 10)->create()->each(function($u){
        $u->roles()->sync(4);
      });
      
      DB::statement('SET FOREIGN_KEY_CHECKS=1');
   }
}