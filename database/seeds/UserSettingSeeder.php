<?php

use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
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
            DB::table('user_settings')->insert([
                'user_id'    => $index,
                'sort_by'    => 'popular',
                'dark_mode'  => 0,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ]);
        }
    }
}
