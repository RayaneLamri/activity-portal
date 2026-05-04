<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Rob Moerman',
            'email' => 'admin@example.com',
            'role' => 'admin'
        ]);

        $usersData = [
            ['name' => 'Marion Lefebvre', 'email' => 'marion@example.com'],
            ['name' => 'Antoine Verhasselt', 'email' => 'antoine@example.com'],
            ['name' => 'Enzo De Wever', 'email' => 'enzo@example.com'],
            ['name' => 'Stephanie Carpentier', 'email' => 'stephanie@example.com', 'is_visible' => false],
            ['name' => 'Maxime Bonte', 'email' => 'maxime@example.com'],
            ['name' => 'Leslie Plas', 'email' => 'leslie@example.com'],
        ];

        foreach ($usersData as $userData) {
            User::factory()->create($userData);
        }
    }
}
