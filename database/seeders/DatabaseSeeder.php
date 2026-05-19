<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\User;
use App\Models\UserPreference;
use Carbon\CarbonImmutable;
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

        $today = CarbonImmutable::today();
        $date = fn (int $days): string => $today->addDays($days)->toDateString();
        $dateTime = fn (int $days, string $time): string => $today
            ->addDays($days)
            ->setTimeFromTimeString($time)
            ->format('Y-m-d H:i:s');

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
                'starts_on' => $date(16),
                'ends_on' => $date(20),
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
                'starts_on' => $date(30),
                'ends_on' => $date(34),
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
                'starts_on' => $date(8),
                'ends_on' => $date(11),
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
                'starts_on' => $date(21),
                'ends_on' => $date(25),
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
                'starts_on' => $date(28),
                'ends_on' => $date(32),
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
                'starts_on' => $date(13),
                'ends_on' => $date(15),
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
                'starts_on' => $date(18),
                'ends_on' => $date(22),
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
                'starts_on' => $date(25),
                'ends_on' => $date(29),
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
                'starts_on' => $date(32),
                'ends_on' => $date(36),
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
                'starts_on' => $date(46),
                'ends_on' => $date(50),
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
                'starts_on' => $date(53),
                'ends_on' => $date(57),
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
                'starts_on' => $date(67),
                'ends_on' => $date(71),
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
                'starts_on' => $date(79),
                'ends_on' => $date(80),
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
                'starts_on' => $date(88),
                'ends_on' => $date(92),
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
                'starts_on' => $date(13),
                'ends_on' => $date(43),
            ],
            [
                'city' => 'Leuven',
                'min_age' => 11,
                'max_age' => 16,
                'starts_on' => $date(7),
                'ends_on' => $date(74),
            ],
            [
                'city' => 'Ghent',
                'min_age' => 9,
                'max_age' => 14,
                'starts_on' => $date(21),
                'ends_on' => $date(75),
            ],
            [
                'city' => 'Namur',
                'min_age' => 12,
                'max_age' => 16,
                'starts_on' => $date(27),
                'ends_on' => $date(27),
            ],
        ];

        foreach ($preferences as $index => $preference) {
            UserPreference::query()->create([
                'user_id' => $users[$index]->id,
                ...$preference,
            ]);
        }

        $registrations = [
            [0, 0, Registration::ACCEPTED, $dateTime(0, '09:00:00')],
            [1, 0, Registration::ACCEPTED, $dateTime(0, '10:00:00')],
            [2, 1, Registration::REQUESTED, $dateTime(1, '11:00:00')],
            [3, 1, Registration::REQUESTED, $dateTime(1, '11:15:00')],
            [4, 1, Registration::REQUESTED, $dateTime(1, '11:30:00')],
            [5, 1, Registration::REQUESTED, $dateTime(1, '11:45:00')],
            [4, 2, Registration::ACCEPTED, $dateTime(3, '13:00:00')],
            [5, 2, Registration::REJECTED, $dateTime(4, '14:00:00')],
            [0, 3, Registration::REQUESTED, $dateTime(5, '15:00:00')],
            [1, 4, Registration::INVITED, $dateTime(5, '16:00:00')],
            [2, 5, Registration::ACCEPTED, $dateTime(6, '09:30:00')],
            [4, 6, Registration::REQUESTED, $dateTime(7, '10:45:00')],
            [5, 7, Registration::ACCEPTED, $dateTime(8, '08:20:00')],
            [0, 8, Registration::INVITED, $dateTime(9, '09:10:00')],
            [2, 9, Registration::REJECTED, $dateTime(10, '10:10:00')],
            [3, 10, Registration::REQUESTED, $dateTime(11, '11:10:00')],
            [4, 11, Registration::ACCEPTED, $dateTime(12, '12:10:00')],
            [5, 13, Registration::REQUESTED, $dateTime(13, '13:10:00')],
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
