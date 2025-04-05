<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentTeacherConversation;

class ParentTeacherConversationSeeder extends Seeder
{
    public function run()
    {
        ParentTeacherConversation::factory()->count(50)->create();
    }
}
