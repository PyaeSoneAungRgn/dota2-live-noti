<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->string('home_team_name');
            $table->string('home_team_logo');
            $table->string('home_team_win')->nullable();
            $table->string('away_team_name');
            $table->string('away_team_logo');
            $table->string('away_team_win')->nullable();
            $table->timestamp('start_at');
            $table->string('timezone');
            $table->string('tournament');
            $table->string('stage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
