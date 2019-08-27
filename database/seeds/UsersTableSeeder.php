<?php
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contact;

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
      Contact::truncate();
      $admin = factory(App\Models\User::class)->create();
      $admin->name = "Admin";
      $admin->email = "admin@gmail.com";
      $admin->password = bcrypt("admin@123");
      $admin->save();
      $admin->roles()->sync(1);

      $admin = factory(App\Models\User::class)->create();
      $admin->name = "Test";
      $admin->email = "test@gmail.com";
      $admin->password = bcrypt("test@123");
      $admin->save();
      $admin->roles()->sync(3);

      $users = factory(App\Models\User::class, 30)->create()->each(function($u){
        $u->roles()->sync(3);
      });

      $contacts = User::get();
      $contacts->each(function($u) use($contacts){
        $contacts = $contacts->filter(function($user) use ($u){
            return $user->id != $u->id;
          })->random(rand(5, 10));

        $u->contacts()->sync($contacts);
      });
      DB::statement('SET FOREIGN_KEY_CHECKS=1');
   }
}