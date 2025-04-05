<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AIChatLog;
use App\Models\User;

class AIChatLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            AIChatLog::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
