<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClubMember;

class ClubMemberSeeder extends Seeder
{
    public function run()
    {
        ClubMember::factory()->count(20)->create();
    }
}
