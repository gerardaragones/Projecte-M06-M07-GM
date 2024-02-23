<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostApiTest extends TestCase
{
    use RefreshDatabase; // Ensure a fresh database for each test

    /**
     * Test listing all posts.
     */
    public function test_index(): void
    {
        $user = User::factory()->create(); // Utiliza la fábrica del modelo User
        $this->actingAs($user);

        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    /**
     * Test creating a new post.
     */
    public function test_store(): void
    {
        $data = [
            'body' => 'Test Body',
            'upload' => base64_encode(file_get_contents(storage_path('app/public/test_image.jpg'))),
            'longitude' => '123.456',
            'latitude' => '78.910',
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'body',
                    'file_id',
                    'latitude',
                    'longitude',
                    'author_id',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Test viewing a specific post.
     */
    public function test_show(): void
    {
        $post = factory(\App\Models\Post::class)->create();

        $response = $this->get("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'body',
                    'file_id',
                    'latitude',
                    'longitude',
                    'author_id',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Test updating a post.
     */
    public function test_update(): void
    {
        $post = factory(\App\Models\Post::class)->create();

        $data = [
            'body' => 'Updated Body',
            'upload' => base64_encode(file_get_contents(storage_path('app/public/test_image.jpg'))),
            'longitude' => '987.654',
            'latitude' => '32.101',
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'body',
                    'file_id',
                    'latitude',
                    'longitude',
                    'author_id',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * Test deleting a post.
     */
    public function test_destroy(): void
    {
        $post = factory(\App\Models\Post::class)->create();

        $response = $this->delete("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'body',
                    'file_id',
                    'latitude',
                    'longitude',
                    'author_id',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertNull(\App\Models\Post::find($post->id));
    }

    /**
     * Test liking a post.
     */
    public function test_like(): void
    {
        $post = factory(\App\Models\Post::class)->create();

        $response = $this->post("/api/posts/{$post->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => 'like creado',
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'post_id' => $post->id,
        ]);
    }

    /**
     * Test unliking a post.
     */
    public function test_unlike(): void
    {
        $like = factory(\App\Models\Like::class)->create(['user_id' => $this->user->id]);

        $response = $this->delete("/api/posts/{$like->post_id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => 'like eliminado',
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'post_id' => $like->post_id,
        ]);
    }
}
