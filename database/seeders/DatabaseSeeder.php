<?php

namespace Database\Seeders;

use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

        $userCount = max((int)$this->command->ask('How many users would you like?', 5), 1);

        // Create an admin user
        User::factory()->admin()->create();
        // Create $userCount normal user
        User::factory($userCount)->create();
    }
}
