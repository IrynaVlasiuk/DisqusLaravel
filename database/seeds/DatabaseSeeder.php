<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(App\User::class, 10)->create();
        factory(User::class, 50)->create()->each(function($u) {
            $u->comments()->save(factory(Comment::class)->make());
        });
    }
}
