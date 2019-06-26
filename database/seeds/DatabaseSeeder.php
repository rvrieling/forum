<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PostSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(LikeSeeder::class);
        $this->call(SubSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProfileSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(UserSettingSeeder::class);
        $this->call(ImagePostSeeder::class);
        $this->call(ChatRoomSeeder::class);
        $this->call(MessageSeeder::class);
    }
}
