<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ParentTeacherNotification;

class ParentTeacherNotificationSeeder extends Seeder
{
    public function run()
    {
        ParentTeacherNotification::factory()->count(50)->create();
    }
}
