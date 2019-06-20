<?php 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1,30) as $index) {
            DB::table('users')->insert([
                'username' => $faker->name,
                'email' => $faker->email,
                'coins' => $faker->numberBetween(0,1000),
                'password' => app('hash')->make('somerandompassword')
            ]);
        }

        foreach (range(1,3) as $index) {
            DB::table('posts')->insert([
                [
                    'title' => $faker->title,
                    'content' => $faker->text,
                    'user_id' => 1
                ]
            ]);
        }

        foreach (range(1,100) as $index) {
            $userid = $faker->numberBetween(1,30);
            $postid = $faker->numberBetween(1,3);
            DB::table('comments')->insert([
                [
                    'type' => $faker->title,
                    'content' => $faker->text,
                    'highlight' => 0,
                    'coins' => 0,
                    'user_id' => $userid,
                    'post_id' => $postid
                ]
            ]);

            DB::table('notifications')->insert([
                [
                    'notification_from' => $userid,
                    'notification_to' => 1,
                    'comment_id' => $faker->numberBetween(1,$index),
                    'post_id' => $postid,
                    'viewed' => NULL,
                ]
            ]);

        }

    }

}