<?php

use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 20) as $index) {
            DB::table('profile')->insert([
                'user_id'      => $index,
                'bio'          => $faker->text,
                'website_link' => $faker->url,
                'image'        => 'default',
                'created_at'   => $faker->dateTime,
                'updated_at'   => $faker->dateTime
            ]);

            DB::table('profile_images')->insert([
                'profile_id'   => $index,
                'image'        => 'default',
                'created_at'   => $faker->dateTime,
                'updated_at'   => $faker->dateTime,
            ]);
        }
    }
}
