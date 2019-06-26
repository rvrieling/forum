<?php

use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        foreach (range(1, 4) as $chatroom) {
            DB::table('chat_rooms')->insert([
                'name'                => $faker->name,
                'most_recent_message' => $faker->text,
                'most_recent_user'    => $faker->name,
                'created_at'          => $faker->dateTime,
                'updated_at'          => $faker->dateTime
            ]);

            foreach (range(1, 8) as $user) {
                DB::table('chatroom_users')->insert([
                    'chat_room_id' => $chatroom,
                    'user_id'      => $user,
                    'is_admin'     => rand(0, 1),
                    'is_read'      => 1,
                    'created_at'   => $faker->dateTime,
                    'updated_at'   => $faker->dateTime
                ]);
            }
        }
    }
}
