<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Registration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->index();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete()->index();
            $table->string('status')->default(Registration::REQUESTED);
            $table->timestamp('date')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['user_id', 'activity_id']);
            $table->index(['activity_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
