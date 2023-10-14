<?php

namespace Database\Seeders;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Channel::factory()
            ->count(5)
            ->create();

        $users = User::all();
        Channel::all()->each(function (Channel $channel) use ($users) {
            $channel->users()->attach(
                $users->random(rand(1, 2))->pluck('id')->toArray()
            );
        });
    }
}
