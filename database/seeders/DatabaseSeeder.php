<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Models\UserPreference;
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
            'role' => 'admin',
        ]);

        $usersData = [
            ['name' => 'Marion Lefebvre', 'email' => 'marion@example.com'],
            ['name' => 'Antoine Verhasselt', 'email' => 'antoine@example.com'],
            ['name' => 'Enzo De Wever', 'email' => 'enzo@example.com'],
            ['name' => 'Stephanie Carpentier', 'email' => 'stephanie@example.com', 'is_visible' => false],
            ['name' => 'Maxime Bonte', 'email' => 'maxime@example.com'],
            ['name' => 'Leslie Plas', 'email' => 'leslie@example.com'],
        ];

        $users = [];

        foreach ($usersData as $userData) {
            $users[] = User::factory()->create($userData);
        }

        $activitiesData = [
            [
                'external_reference' => 'ACT-1001',
                'title' => 'Spring City Camp',
                'description' => 'Five-day city activity program focused on teamwork, planning, and creative workshops.',
                'location_name' => 'Riverside Youth Hub',
                'city' => 'Brussels',
                'min_age' => 8,
                'max_age' => 12,
                'starts_on' => '2026-07-04',
                'ends_on' => '2026-07-08',
                'capacity' => 2,
            ],
            [
                'external_reference' => 'ACT-1002',
                'title' => 'Forest Discovery Week',
                'description' => 'Nature and outdoor games for younger participants.',
                'location_name' => 'Bois Vert Lodge',
                'city' => 'Namur',
                'min_age' => 7,
                'max_age' => 11,
                'starts_on' => '2026-07-18',
                'ends_on' => '2026-07-22',
                'capacity' => 4,
            ],
            [
                'external_reference' => 'ACT-1003',
                'title' => 'Robotics Sprint',
                'description' => 'Hands-on robotics and coding sessions for older children.',
                'location_name' => 'Tech Lab Leuven',
                'city' => 'Leuven',
                'min_age' => 11,
                'max_age' => 15,
                'starts_on' => '2026-06-02',
                'ends_on' => '2026-06-05',
                'capacity' => 3,
            ],
            [
                'external_reference' => 'ACT-1004',
                'title' => 'Arts and Stories Retreat',
                'description' => 'Creative storytelling, drawing, and group activities.',
                'location_name' => 'Canvas House',
                'city' => 'Ghent',
                'min_age' => 9,
                'max_age' => 14,
                'starts_on' => '2026-06-15',
                'ends_on' => '2026-06-19',
                'capacity' => 6,
            ],
            [
                'external_reference' => 'ACT-1005',
                'title' => 'Indoor Sports Academy',
                'description' => 'Structured sports sessions in a large indoor facility.',
                'location_name' => 'Arena Antwerp',
                'city' => 'Antwerp',
                'min_age' => 10,
                'max_age' => 16,
                'starts_on' => '2026-06-22',
                'ends_on' => '2026-06-26',
                'capacity' => 8,
            ],
            [
                'external_reference' => 'ACT-1006',
                'title' => 'Summer Makers Lab',
                'description' => 'Crafting, prototyping, and collaborative maker sessions.',
                'location_name' => 'Station 12',
                'city' => 'Brussels',
                'min_age' => 10,
                'max_age' => 15,
                'starts_on' => '2026-07-01',
                'ends_on' => '2026-07-03',
                'capacity' => 5,
            ],
            [
                'external_reference' => 'ACT-1007',
                'title' => 'River Adventure Camp',
                'description' => 'Water safety, outdoor learning, and team challenges.',
                'location_name' => 'Meuse Riverside Base',
                'city' => 'Namur',
                'min_age' => 12,
                'max_age' => 16,
                'starts_on' => '2026-07-06',
                'ends_on' => '2026-07-10',
                'capacity' => 4,
            ],
            [
                'external_reference' => 'ACT-1008',
                'title' => 'Junior Science Week',
                'description' => 'Simple science experiments and guided activities.',
                'location_name' => 'Discovery Hall',
                'city' => 'Ghent',
                'min_age' => 8,
                'max_age' => 13,
                'starts_on' => '2026-07-13',
                'ends_on' => '2026-07-17',
                'capacity' => 7,
            ],
            [
                'external_reference' => 'ACT-1009',
                'title' => 'Digital Media Workshop',
                'description' => 'Audio, photo, and short video exercises for teens.',
                'location_name' => 'Pixel Studio',
                'city' => 'Leuven',
                'min_age' => 13,
                'max_age' => 17,
                'starts_on' => '2026-07-20',
                'ends_on' => '2026-07-24',
                'capacity' => 3,
            ],
            [
                'external_reference' => 'ACT-1010',
                'title' => 'Neighborhood Play Days',
                'description' => 'Play-based activities spread across several city locations.',
                'location_name' => 'Community Square',
                'city' => 'Brussels',
                'min_age' => 6,
                'max_age' => 10,
                'starts_on' => '2026-08-03',
                'ends_on' => '2026-08-07',
                'capacity' => 10,
            ],
            [
                'external_reference' => 'ACT-1011',
                'title' => 'Music and Rhythm Camp',
                'description' => 'Daily rhythm sessions, group singing, and simple performance prep.',
                'location_name' => 'Studio Noord',
                'city' => 'Antwerp',
                'min_age' => 9,
                'max_age' => 13,
                'starts_on' => '2026-08-10',
                'ends_on' => '2026-08-14',
                'capacity' => null,
            ],
            [
                'external_reference' => 'ACT-1012',
                'title' => 'Design Challenge Week',
                'description' => 'Collaborative design sprints and presentation practice.',
                'location_name' => 'Workshop 21',
                'city' => 'Leuven',
                'min_age' => 12,
                'max_age' => 16,
                'starts_on' => '2026-08-24',
                'ends_on' => '2026-08-28',
                'capacity' => 6,
            ],
            [
                'external_reference' => 'ACT-1013',
                'title' => 'Autumn Prep Weekend',
                'description' => 'Short staff-supported program for planning the new season.',
                'location_name' => 'Central Hall',
                'city' => 'Ghent',
                'min_age' => 10,
                'max_age' => 15,
                'starts_on' => '2026-09-05',
                'ends_on' => '2026-09-06',
                'capacity' => 5,
            ],
            [
                'external_reference' => 'ACT-1014',
                'title' => 'Urban Exploration Camp',
                'description' => 'Museum visits, city discovery, and guided team activities.',
                'location_name' => 'North Gate Campus',
                'city' => 'Brussels',
                'min_age' => 11,
                'max_age' => 15,
                'starts_on' => '2026-09-14',
                'ends_on' => '2026-09-18',
                'capacity' => 4,
            ],
        ];

        $activities = [];

        foreach ($activitiesData as $activityData) {
            $activities[] = Activity::query()->create($activityData);
        }

        $preferences = [
            [
                'city' => 'Brussels',
                'min_age' => 8,
                'max_age' => 13,
                'starts_on' => '2026-07-01',
                'ends_on' => '2026-07-31',
            ],
            [
                'city' => 'Leuven',
                'min_age' => 11,
                'max_age' => 16,
                'starts_on' => '2026-06-01',
                'ends_on' => '2026-08-31',
            ],
            [
                'city' => 'Ghent',
                'min_age' => 9,
                'max_age' => 14,
                'starts_on' => '2026-06-15',
                'ends_on' => '2026-09-01',
            ],
            [
                'city' => 'Namur',
                'min_age' => 12,
                'max_age' => 16,
                'starts_on' => '2026-07-15',
                'ends_on' => '2026-07-15',
            ],
        ];

        foreach ($preferences as $index => $preference) {
            UserPreference::query()->create([
                'user_id' => $users[$index]->id,
                ...$preference,
            ]);
        }

        $registrations = [
            [0, 0, Registration::ACCEPTED, '2026-04-01 09:00:00'],
            [1, 0, Registration::ACCEPTED, '2026-04-02 10:00:00'],
            [2, 1, Registration::REQUESTED, '2026-04-03 11:00:00'],
            [3, 1, Registration::REQUESTED, '2026-04-03 11:00:00'],
            [4, 1, Registration::REQUESTED, '2026-04-03 11:00:00'],
            [5, 1, Registration::REQUESTED, '2026-04-03 11:00:00'],
            [3, 2, Registration::INVITED, '2026-04-04 12:00:00'],
            [4, 2, Registration::ACCEPTED, '2026-04-05 13:00:00'],
            [5, 2, Registration::REJECTED, '2026-04-06 14:00:00'],
            [0, 3, Registration::REQUESTED, '2026-04-07 15:00:00'],
            [1, 4, Registration::INVITED, '2026-04-08 16:00:00'],
            [2, 5, Registration::ACCEPTED, '2026-04-09 09:30:00'],
            [4, 6, Registration::REQUESTED, '2026-04-10 10:45:00'],
            [5, 7, Registration::ACCEPTED, '2026-04-11 08:20:00'],
            [0, 8, Registration::INVITED, '2026-04-12 09:10:00'],
            [2, 9, Registration::REJECTED, '2026-04-13 10:10:00'],
            [3, 10, Registration::REQUESTED, '2026-04-14 11:10:00'],
            [4, 11, Registration::ACCEPTED, '2026-04-15 12:10:00'],
            [5, 13, Registration::REQUESTED, '2026-04-16 13:10:00'],
        ];

        foreach ($registrations as [$userIndex, $activityIndex, $status, $date]) {
            Registration::query()->create([
                'user_id' => $users[$userIndex]->id,
                'activity_id' => $activities[$activityIndex]->id,
                'status' => $status,
                'date' => $date,
            ]);
        }
    }
}
