<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Ask questions from user
        if ($this->command->confirm('Want to refresh database?', true)) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database is seeded');
        }

        // Flush all caches
        Cache::tags(['books'])->flush();
        Cache::tags(['authors'])->flush();
        Cache::tags(['comments'])->flush();
        Cache::tags(['publishers'])->flush();
        Cache::tags(['categories'])->flush();

        // Call seeders
        $this->call([
            UserSeeder::class,
            AuthorPublisherCategorySeeder::class,
            BookSeeder::class,
            BookAuthorCategorySeeder::class,
            CommentSeeder::class
        ]);
    }
}
