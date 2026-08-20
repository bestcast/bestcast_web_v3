<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movies;
use App\Models\MediaFolder;
use App\Models\Media;

class BackfillMovieFolders extends Command
{
    protected $signature = 'movies:backfill-folders';
    protected $description = 'Create a media folder for every existing movie and assign its linked images to it';

    public function handle()
    {
        $movies = Movies::all();
        $imageFields = ['image_id', 'medium_id', 'thumbnail_id', 'portraitsmall_id', 'portrait_id'];

        $created = 0;
        $assigned = 0;

        foreach ($movies as $movie) {
            $folder = MediaFolder::where('type', 'movie')
                        ->where('reference_id', $movie->id)
                        ->first();

            if (empty($folder)) {
                $folder = MediaFolder::create([
                    'name'         => $movie->id . ' - ' . $movie->title,
                    'type'         => 'movie',
                    'reference_id' => $movie->id,
                    'created_by'   => 1, // adjust to a valid admin user id
                ]);
                $created++;
            }

            foreach ($imageFields as $field) {
                if (!empty($movie->$field)) {
                    $updated = Media::where('id', $movie->$field)
                                ->whereNull('folder_id')
                                ->update(['folder_id' => $folder->id]);
                    $assigned += $updated;
                }
            }
        }

        $this->info("Done. Folders created: {$created}. Media files assigned: {$assigned}.");
    }
}