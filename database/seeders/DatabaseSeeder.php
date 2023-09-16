<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call([
        PermissionTableSeeder::class,
        JobSeeder::class,
        UserSeeder::class,
        SettingSystem::class
      ]);
      // $this->call(JobSeeder::class);
      // $this->call(UserSeeder::class);
      // $this->call(SettingSystem::class);
        // \App\Models\User::factory(10)->create();
    }
}
