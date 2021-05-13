<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = max((int)$this->command->ask('How many users would you like?', 5), 1);

        // Create an admin user
        User::factory(3)->admin()->create();
        // Create $userCount normal user
        User::factory($userCount)->create();
    }
}
