<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Voter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role' => 'Admin',
        ]);
        $voter = User::factory()->create([
                        'name' => 'Voter',
                        'email' => 'voter@voter.com',
                        'password' => bcrypt('voter'),
                    ]);
        Voter::factory()->create([
            'user_id' => $voter->id,
        ]);
    }
}
