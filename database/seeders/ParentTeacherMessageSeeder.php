<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentTeacherMessage;

class ParentTeacherMessageSeeder extends Seeder
{
    public function run()
    {
        ParentTeacherMessage::factory()->count(50)->create();
    }
}
