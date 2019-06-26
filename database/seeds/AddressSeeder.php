<?php

use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
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
            DB::table('addresses')->insert([
                'user_id'     => $index,
                'streetname'  => $faker->streetName,
                'housenumber' => $faker->buildingNumber,
                'zipcode'     => $faker->postCode,
                'city'        => $faker->city,
                'state'       => $faker->state,
                'country'     => $faker->country,
                'created_at'  => $faker->dateTime,
                'updated_at'  => $faker->dateTime
            ]);
        }
    }
}
