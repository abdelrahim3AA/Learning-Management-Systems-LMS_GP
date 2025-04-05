<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClubChatMessage;

class ClubChatMessageSeeder extends Seeder
{
    public function run()
    {
        ClubChatMessage::factory()->count(50)->create();
    }
}
