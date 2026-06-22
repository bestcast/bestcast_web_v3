<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\User;
use App\Models\Movies;
use App\Models\UsersMovies;

class QuizPromptSkippedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that skipping a quiz prompt works even if tokenEncrypted is missing.
     */
    public function test_quiz_prompt_skipped_handles_missing_token_gracefully(): void
    {
        // 1. Create a dummy user and movie
        $user = User::factory()->create();
        $movie = Movies::create([
            'title' => 'Test Movie',
            'video_url' => 'http://example.com/video.mp4',
            'movie_quiz_status' => 1,
            'urlkey' => 'test-movie-' . uniqid(),
        ]);

        // 2. Initialize a users_movies relation record
        UsersMovies::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'quiz_prompt_shown' => 1,
        ]);

        // 3. Act as the user and post to skipped endpoint without tokenEncrypted
        $response = $this->actingAs($user)->postJson('/api/quiz-prompt-skipped', [
            'movie_id' => $movie->id,
            // tokenEncrypted is intentionally missing
        ]);

        // 4. Assert that the request succeeds (returns 204 No Content)
        $response->assertStatus(204);

        // 5. Verify database was updated successfully
        $this->assertDatabaseHas('users_movies', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'quiz_prompt_shown' => 0,
        ]);
    }
}
