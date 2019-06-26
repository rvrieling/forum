<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 30) as $index) {
            DB::table('posts')->insert([
                'category_id' => 1,
                'user_id'     => 1,
                'name'        => $faker->text,
                'content'     => $faker->text,
                'likes_count' => rand(1, 1000000),
                'created_at'  => $faker->dateTime,
                'updated_at'  => $faker->dateTime
            ]);
        }

        foreach (range(1, 30) as $index) {
            DB::table('posts')->insert([
                'category_id' => 2,
                'user_id'     => 1,
                'name'        => $faker->text,
                'content'     => $faker->text,
                'likes_count' => rand(1, 1000000),
                'created_at'  => $faker->dateTime,
                'updated_at'  => $faker->dateTime
            ]);
        }
    }
}
