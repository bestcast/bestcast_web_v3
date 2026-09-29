<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // season_id, webseries_id, and their index were already added
        // in 2026_09_15_115119_userepisode_altertable.
        // This migration only backfills existing rows.

        DB::table('users_episodes')
            ->join('episodes', 'episodes.id', '=', 'users_episodes.episode_id')
            ->update([
                'users_episodes.season_id' => DB::raw('episodes.season_id'),
            ]);

        DB::table('users_episodes')
            ->join('episodes', 'episodes.id', '=', 'users_episodes.episode_id')
            ->join('seasons', 'seasons.id', '=', 'episodes.season_id')
            ->update([
                'users_episodes.webseries_id' => DB::raw('seasons.webseries_id'),
            ]);
    }

    public function down(): void
    {
        // No-op: backfill isn't meaningfully reversible,
        // and the columns/index are owned by the earlier migration.
    }
};