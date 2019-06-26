<?php

use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        
        foreach (range(1, 60) as $index) {
            DB::table('likes')->insert([
               'user_id'     => 1,
               'post_id'     => $index,
               'liked'       => 0,
               'created_at'  => $faker->dateTime,
                'updated_at' => $faker->dateTime
           ]);
        }
    }
}
