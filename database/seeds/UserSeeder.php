<?php

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
        $faker = Faker\Factory::create();
        DB::table('users')->insert([
            'first_name'     => 'admin',
            'last_name'      => 'admin',
            'user_name'      => 'admin',
            'email'          => 'admin@admin.nl',
            'password'       => Hash::make('admin'),
            'role'           => 'admin',
            'created_at'     => $faker->dateTime,
            'updated_at'     => $faker->dateTime
        ]);

        DB::table('api_tokens')->insert([
            'api_token' => str_random(80),
            'user_id'   => 1,
        ]);

        foreach (range(1, 19) as $index) {
            DB::table('users')->insert([
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'user_name'  => $faker->userName,
                'email'      => $faker->email,
                'password'   => Hash::make($faker->password),
                'role'       => 'user',
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ]);
        }

        foreach (range(2, 20) as $index) {
            DB::table('api_tokens')->insert([
                'api_token' => str_random(80),
                'user_id'   => $index,
            ]);
        }
    }
}
