<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_consegue_enviar_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('profile.avatar'), [
            'avatar' => UploadedFile::fake()->create('foto.jpg', 500, 'image/jpeg'),
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'avatar-updated');

        $user->refresh();
        $this->assertNotNull($user->avatar_path, 'avatar_path nao foi salvo no banco');
        Storage::disk('public')->assertExists($user->avatar_path);
    }
}
