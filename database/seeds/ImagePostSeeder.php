<?php

use Illuminate\Database\Seeder;

class ImagePostSeeder extends Seeder
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
            DB::table('image_posts')->insert([
                'post_id'    => $index,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ]);
        }
    }
}
