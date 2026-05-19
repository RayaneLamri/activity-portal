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
            $table->string('from_status')->nullable()->after('action');
            $table->string('to_status')->nullable()->after('from_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_events', function (Blueprint $table) {
            $table->dropColumn(['from_status', 'to_status']);
        });
    }
};
