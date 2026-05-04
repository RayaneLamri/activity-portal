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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('external_reference')->nullable()->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location_name');
            $table->string('city')->nullable()->index();
            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();
            $table->date('starts_on')->index();
            $table->date('ends_on');
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('is_active')->default(true)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
