<?php

use Illuminate\Database\Seeder;

class SubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 2) as $cat) {
            foreach (range(1, 20) as $user) {
                Db::table('subs')->insert([
                    'category_id' => $cat,
                    'user_id'     => $user,
                    'subscribed'  => 0,
                    'created_at'  => $faker->dateTime,
                    'updated_at'  => $faker->dateTime
                ]);
            }
        }
    }
}
