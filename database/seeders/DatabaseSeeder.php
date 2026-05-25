<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Registration;
use App\Models\RegistrationEvent;
use App\Models\User;
use App\Models\UserPreference;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        $dateTime = fn (int $days, string $time): string => $today
            ->addDays($days)
            ->setTimeFromTimeString($time)
            ->format('Y-m-d H:i:s');
        $before = fn (string $date, int $minutes): string => CarbonImmutable::parse($date)
            ->subMinutes($minutes)
            ->format('Y-m-d H:i:s');

        $seedUser = function (array $userData): User {
            $user = User::query()->firstOrNew(['email' => $userData['email']]);

            $attributes = [
                'name' => $userData['name'],
                'role' => $userData['role'] ?? 'user',
                'is_visible' => $userData['is_visible'] ?? true,
            ];

            if (! $user->exists) {
                $attributes += [
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ];
            }

            $user->forceFill($attributes)->save();

            return $user;
        };

        $seedUser([
            'name' => 'Rob Moerman',
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);

        $usersData = [
            ['name' => 'Marion Lefebvre', 'email' => 'marion@example.test'],
            ['name' => 'Antoine Verhasselt', 'email' => 'antoine@example.test'],
            ['name' => 'Enzo De Wever', 'email' => 'enzo@example.test'],
            ['name' => 'Stephanie Carpentier', 'email' => 'stephanie@example.test', 'is_visible' => false],
            ['name' => 'Maxime Bonte', 'email' => 'maxime@example.test'],
            ['name' => 'Leslie Plas', 'email' => 'leslie@example.test'],
        ];

        $users = [];

        foreach ($usersData as $userData) {
            $users[] = $seedUser($userData);
        }

        $periodsData = [
            'spring_2026_past' => [
                'name' => 'Spring Break 2026',
                'starts_on' => '2026-04-27',
                'ends_on' => '2026-05-01',
            ],
            'summer_2026_early' => [
                'name' => 'Summer Program 2026 - Early July',
                'starts_on' => '2026-07-06',
                'ends_on' => '2026-07-10',
            ],
            'summer_2026_late' => [
                'name' => 'Summer Program 2026 - Mid August',
                'starts_on' => '2026-08-10',
                'ends_on' => '2026-08-14',
            ],
            'autumn_2026_early' => [
                'name' => 'Autumn Break 2026',
                'starts_on' => '2026-10-26',
                'ends_on' => '2026-10-30',
            ],
            'winter_2026_2027' => [
                'name' => 'Winter Break 2026 - Christmas',
                'starts_on' => '2026-12-21',
                'ends_on' => '2026-12-24',
            ],
        ];

        $activitiesData = [
            [
                'external_reference' => 'ACT-1001',
                'title' => 'Brussels Creative Week',
                'description' => 'Creative workshops and group activities for younger participants.',
                'location_name' => 'Park Youth House',
                'city' => 'Brussels',
                'period_name' => $periodsData['summer_2026_early']['name'],
                'starts_on' => $periodsData['summer_2026_early']['starts_on'],
                'ends_on' => $periodsData['summer_2026_early']['ends_on'],
                'age_group' => Activity::AGE_LOWER,
                'min_age' => 8,
                'max_age' => 12,
                'capacity' => 2,
            ],
            [
                'external_reference' => 'ACT-1002',
                'title' => 'Junior Science Week',
                'description' => 'Simple science experiments and guided activities.',
                'location_name' => 'Centre Nature du Bourgoyen',
                'city' => 'Ghent',
                'period_name' => $periodsData['summer_2026_early']['name'],
                'starts_on' => $periodsData['summer_2026_early']['starts_on'],
                'ends_on' => $periodsData['summer_2026_early']['ends_on'],
                'age_group' => Activity::AGE_LOWER,
                'min_age' => 8,
                'max_age' => 13,
                'capacity' => 4,
            ],
            [
                'external_reference' => 'ACT-1003',
                'title' => 'Indoor Sports Academy',
                'description' => 'Structured sports sessions in a large indoor facility.',
                'location_name' => 'Sporthal Rivierenhof',
                'city' => 'Antwerp',
                'period_name' => $periodsData['summer_2026_late']['name'],
                'starts_on' => $periodsData['summer_2026_late']['starts_on'],
                'ends_on' => $periodsData['summer_2026_late']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 10,
                'max_age' => 16,
                'capacity' => 3,
            ],
            [
                'external_reference' => 'ACT-1004',
                'title' => 'Digital Media Workshop',
                'description' => 'Audio, photo, and short video exercises for teens.',
                'location_name' => 'Atelier Vaartkom',
                'city' => 'Leuven',
                'period_name' => $periodsData['summer_2026_late']['name'],
                'starts_on' => $periodsData['summer_2026_late']['starts_on'],
                'ends_on' => $periodsData['summer_2026_late']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 13,
                'max_age' => 17,
                'capacity' => 2,
            ],
            [
                'external_reference' => 'ACT-1005',
                'title' => 'River Adventure Camp',
                'description' => 'Water safety, outdoor learning, and team challenges.',
                'location_name' => 'Meuse Outdoor Base',
                'city' => 'Namur',
                'period_name' => $periodsData['summer_2026_late']['name'],
                'starts_on' => $periodsData['summer_2026_late']['starts_on'],
                'ends_on' => $periodsData['summer_2026_late']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 12,
                'max_age' => 16,
                'capacity' => 3,
            ],
            [
                'external_reference' => 'ACT-1006',
                'title' => 'Summer Makers Lab',
                'description' => 'Crafting, prototyping, and collaborative maker sessions.',
                'location_name' => 'Tanneurs FabLab',
                'city' => 'Brussels',
                'period_name' => $periodsData['summer_2026_late']['name'],
                'starts_on' => $periodsData['summer_2026_late']['starts_on'],
                'ends_on' => $periodsData['summer_2026_late']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 10,
                'max_age' => 15,
                'capacity' => 1,
            ],
            [
                'external_reference' => 'ACT-1007',
                'title' => 'Autumn Stories Retreat',
                'description' => 'Creative storytelling, drawing, and group activities.',
                'location_name' => 'De Sterre Estate',
                'city' => 'Ghent',
                'period_name' => $periodsData['autumn_2026_early']['name'],
                'starts_on' => $periodsData['autumn_2026_early']['starts_on'],
                'ends_on' => $periodsData['autumn_2026_early']['ends_on'],
                'age_group' => Activity::AGE_LOWER,
                'min_age' => 9,
                'max_age' => 14,
                'capacity' => 4,
            ],
            [
                'external_reference' => 'ACT-1008',
                'title' => 'Urban Exploration Camp',
                'description' => 'Museum visits, city discovery, and guided team activities.',
                'location_name' => 'Botanique Hostel',
                'city' => 'Brussels',
                'period_name' => $periodsData['winter_2026_2027']['name'],
                'starts_on' => $periodsData['winter_2026_2027']['starts_on'],
                'ends_on' => $periodsData['winter_2026_2027']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 11,
                'max_age' => 15,
                'capacity' => 4,
            ],
            [
                'external_reference' => 'ACT-1009',
                'title' => 'Spring Team Days',
                'description' => 'Past activity kept in the demo data to illustrate archived registrations.',
                'location_name' => 'Centre De Feniks',
                'city' => 'Ghent',
                'period_name' => $periodsData['spring_2026_past']['name'],
                'starts_on' => $periodsData['spring_2026_past']['starts_on'],
                'ends_on' => $periodsData['spring_2026_past']['ends_on'],
                'age_group' => Activity::AGE_LOWER,
                'min_age' => 8,
                'max_age' => 12,
                'capacity' => 3,
            ],
            [
                'external_reference' => 'ACT-1010',
                'title' => 'Outdoor Discovery Week',
                'description' => 'Nature games, orientation activities, and small group challenges.',
                'location_name' => 'Blaarmeersen Activity Centre',
                'city' => 'Ghent',
                'period_name' => $periodsData['summer_2026_early']['name'],
                'starts_on' => $periodsData['summer_2026_early']['starts_on'],
                'ends_on' => $periodsData['summer_2026_early']['ends_on'],
                'age_group' => Activity::AGE_LOWER,
                'min_age' => 8,
                'max_age' => 12,
                'capacity' => 6,
            ],
            [
                'external_reference' => 'ACT-1011',
                'title' => 'Creative Tech Lab',
                'description' => 'Collaborative coding, design, and playful digital creation.',
                'location_name' => 'Makerspace Zuid',
                'city' => 'Antwerp',
                'period_name' => $periodsData['summer_2026_late']['name'],
                'starts_on' => $periodsData['summer_2026_late']['starts_on'],
                'ends_on' => $periodsData['summer_2026_late']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 12,
                'max_age' => 16,
                'capacity' => 5,
            ],
            [
                'external_reference' => 'ACT-1012',
                'title' => 'Team Challenge Days',
                'description' => 'Cooperative games, problem solving, and outdoor team missions.',
                'location_name' => 'Domein Heverlee',
                'city' => 'Leuven',
                'period_name' => $periodsData['autumn_2026_early']['name'],
                'starts_on' => $periodsData['autumn_2026_early']['starts_on'],
                'ends_on' => $periodsData['autumn_2026_early']['ends_on'],
                'age_group' => Activity::AGE_UPPER,
                'min_age' => 11,
                'max_age' => 15,
                'capacity' => 4,
            ],
        ];

        $activities = [];

        foreach ($activitiesData as $activityData) {
            $activities[] = Activity::query()->updateOrCreate(
                ['external_reference' => $activityData['external_reference']],
                $activityData
            );
        }

        $preferences = [
            [
                'city' => 'Ghent',
                'cities' => ['Ghent', 'Antwerp', 'Leuven'],
                'period_names' => [
                    $periodsData['summer_2026_early']['name'],
                    $periodsData['summer_2026_late']['name'],
                    $periodsData['autumn_2026_early']['name'],
                ],
                'age_groups' => [Activity::AGE_LOWER, Activity::AGE_UPPER],
                'min_age' => 8,
                'max_age' => 16,
                'starts_on' => '2026-07-06',
                'ends_on' => '2026-10-30',
            ],
            [
                'city' => 'Brussels',
                'cities' => ['Brussels'],
                'period_names' => [$periodsData['summer_2026_late']['name']],
                'age_groups' => [Activity::AGE_UPPER],
                'min_age' => 10,
                'max_age' => 15,
                'starts_on' => '2026-08-10',
                'ends_on' => '2026-08-14',
            ],
            [
                'city' => 'Namur',
                'cities' => ['Namur'],
                'period_names' => [$periodsData['summer_2026_late']['name']],
                'age_groups' => [Activity::AGE_UPPER],
                'min_age' => 12,
                'max_age' => 16,
                'starts_on' => '2026-08-10',
                'ends_on' => '2026-08-14',
            ],
            [
                'city' => 'Brussels',
                'cities' => ['Brussels'],
                'period_names' => [$periodsData['winter_2026_2027']['name']],
                'age_groups' => [Activity::AGE_UPPER],
                'min_age' => 11,
                'max_age' => 15,
                'starts_on' => '2026-12-21',
                'ends_on' => '2026-12-24',
            ],
            [
                'city' => 'Brussels',
                'cities' => ['Brussels'],
                'period_names' => [$periodsData['summer_2026_late']['name']],
                'age_groups' => [Activity::AGE_UPPER],
                'min_age' => 10,
                'max_age' => 15,
                'starts_on' => '2026-08-10',
                'ends_on' => '2026-08-14',
            ],
            [
                'city' => 'Ghent',
                'cities' => ['Ghent'],
                'period_names' => [
                    $periodsData['summer_2026_early']['name'],
                    $periodsData['autumn_2026_early']['name'],
                ],
                'age_groups' => [Activity::AGE_LOWER],
                'min_age' => 8,
                'max_age' => 14,
                'starts_on' => '2026-07-06',
                'ends_on' => '2026-10-30',
            ],
        ];

        foreach ($preferences as $index => $preference) {
            UserPreference::query()->updateOrCreate(
                ['user_id' => $users[$index]->id],
                $preference
            );
        }

        $registrations = [
            [0, 1, Registration::REQUESTED, $dateTime(-1, '09:00:00'), null],
            [0, 2, Registration::INVITED, $dateTime(-1, '10:00:00'), null],
            [0, 3, Registration::ACCEPTED, $dateTime(-2, '11:00:00'), Registration::INVITED],
            [0, 4, Registration::REJECTED, $dateTime(-2, '12:00:00'), Registration::REQUESTED],
            [1, 0, Registration::REQUESTED, $dateTime(-3, '09:30:00'), null],
            [1, 5, Registration::REQUESTED, $dateTime(-3, '10:30:00'), null],
            [2, 0, Registration::ACCEPTED, $dateTime(-4, '11:30:00'), Registration::REQUESTED],
            [3, 7, Registration::REQUESTED, $dateTime(-4, '12:30:00'), null],
            [4, 5, Registration::ACCEPTED, $dateTime(-5, '13:30:00'), Registration::REQUESTED],
            [5, 6, Registration::REQUESTED, $dateTime(-5, '14:30:00'), null],
            [0, 8, Registration::ACCEPTED, $dateTime(-12, '09:30:00'), Registration::REQUESTED],
        ];

        foreach ($registrations as [$userIndex, $activityIndex, $status, $date, $initialStatus]) {
            $registration = Registration::query()->updateOrCreate(
                [
                    'user_id' => $users[$userIndex]->id,
                    'activity_id' => $activities[$activityIndex]->id,
                ],
                [
                    'status' => $status,
                    'date' => $date,
                ]
            );

            $firstStatus = $initialStatus ?? $status;

            RegistrationEvent::query()->updateOrCreate(
                [
                    'registration_id' => $registration->id,
                    'action' => $firstStatus,
                    'to_status' => $firstStatus,
                ],
                [
                    'from_status' => null,
                    'date' => $initialStatus ? $before($date, 180) : $date,
                ]
            );

            if ($initialStatus) {
                RegistrationEvent::query()->updateOrCreate(
                    [
                        'registration_id' => $registration->id,
                        'action' => $status,
                        'to_status' => $status,
                    ],
                    [
                        'from_status' => $initialStatus,
                        'date' => $date,
                    ]
                );
            }
        }
    }
}
