<?php

use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 8) as $user) {
            foreach (range(1, 4) as $room) {
                foreach (range(1, 20) as $index) {
                    DB::table('chat_messages')->insert([
                        'chat_room_id' => $room,
                        'user_id'      => $user,
                        'message'      => $faker->text,
                        'created_at'   => $faker->dateTime,
                        'updated_at'   => $faker->dateTime,
                    ]);
                }
            }
        }
    }
}
