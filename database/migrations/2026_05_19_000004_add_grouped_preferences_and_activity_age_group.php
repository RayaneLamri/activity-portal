<?php

use App\Models\Activity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (! Schema::hasColumn('activities', 'age_group')) {
                $table->string('age_group')->nullable()->index()->after('max_age');
            }
        });

        Schema::table('user_preferences', function (Blueprint $table) {
            if (! Schema::hasColumn('user_preferences', 'cities')) {
                $table->json('cities')->nullable()->after('city');
            }

            if (! Schema::hasColumn('user_preferences', 'period_names')) {
                $table->json('period_names')->nullable()->after('ends_on');
            }

            if (! Schema::hasColumn('user_preferences', 'age_groups')) {
                $table->json('age_groups')->nullable()->after('period_names');
            }
        });

        DB::table('activities')
            ->whereNull('age_group')
            ->orderBy('id')
            ->get(['id', 'min_age', 'max_age'])
            ->each(function ($activity): void {
                $ageGroup = match (true) {
                    $activity->max_age !== null && $activity->max_age <= 8 => Activity::AGE_PRESCHOOL,
                    $activity->min_age !== null && $activity->min_age >= 10 => Activity::AGE_UPPER,
                    default => Activity::AGE_LOWER,
                };

                DB::table('activities')
                    ->where('id', $activity->id)
                    ->update(['age_group' => $ageGroup]);
            });

        DB::table('user_preferences')
            ->orderBy('id')
            ->get()
            ->each(function ($preference): void {
                $updates = [];

                if ($preference->cities === null && filled($preference->city)) {
                    $updates['cities'] = json_encode([$preference->city]);
                }

                if ($preference->age_groups === null) {
                    $updates['age_groups'] = json_encode($this->ageGroupsForPreference($preference->min_age, $preference->max_age));
                }

                if ($preference->period_names === null) {
                    $periodNames = DB::table('activities')
                        ->when($preference->starts_on, fn ($query, $date) => $query->whereDate('ends_on', '>=', $date))
                        ->when($preference->ends_on, fn ($query, $date) => $query->whereDate('starts_on', '<=', $date))
                        ->orderBy('starts_on')
                        ->distinct()
                        ->pluck('period_name')
                        ->filter()
                        ->values()
                        ->all();

                    $updates['period_names'] = json_encode($periodNames);
                }

                if ($updates !== []) {
                    DB::table('user_preferences')
                        ->where('id', $preference->id)
                        ->update($updates);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            if (Schema::hasColumn('user_preferences', 'age_groups')) {
                $table->dropColumn('age_groups');
            }

            if (Schema::hasColumn('user_preferences', 'period_names')) {
                $table->dropColumn('period_names');
            }

            if (Schema::hasColumn('user_preferences', 'cities')) {
                $table->dropColumn('cities');
            }
        });

        Schema::table('activities', function (Blueprint $table) {
            if (Schema::hasColumn('activities', 'age_group')) {
                $table->dropIndex(['age_group']);
                $table->dropColumn('age_group');
            }
        });
    }

    private function ageGroupsForPreference(?int $minAge, ?int $maxAge): array
    {
        if ($minAge === null && $maxAge === null) {
            return [];
        }

        return collect(Activity::ageGroups())
            ->filter(function (array $group) use ($minAge, $maxAge): bool {
                if ($minAge !== null && $group['max'] < $minAge) {
                    return false;
                }

                if ($maxAge !== null && $group['min'] > $maxAge) {
                    return false;
                }

                return true;
            })
            ->keys()
            ->values()
            ->all();
    }
};
