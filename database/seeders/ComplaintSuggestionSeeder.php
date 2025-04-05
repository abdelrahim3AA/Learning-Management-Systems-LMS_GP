<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComplaintSuggestion;
use App\Models\User;

class ComplaintSuggestionSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            ComplaintSuggestion::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
