<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registration_events', function (Blueprint $table) {
            if (Schema::hasColumn('registration_events', 'from_status')) {
                $table->dropColumn('from_status');
            }

            if (Schema::hasColumn('registration_events', 'to_status')) {
                $table->dropColumn('to_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_events', function (Blueprint $table) {
            if (! Schema::hasColumn('registration_events', 'from_status')) {
                $table->string('from_status')->nullable()->after('action');
            }

            if (! Schema::hasColumn('registration_events', 'to_status')) {
                $table->string('to_status')->nullable()->after('from_status');
            }
        });
    }
};
