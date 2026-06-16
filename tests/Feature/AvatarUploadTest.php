<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_consegue_enviar_avatar(): void
    {
        $user = User::factory()->create();

        // PNG 1x1 real (conteudo de verdade, sem precisar de GD)
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M8AAAMBAQDJ/pLvAAAAAElFTkSuQmCC');

        $response = $this->actingAs($user)->post(route('profile.avatar'), [
            'avatar' => UploadedFile::fake()->createWithContent('foto.png', $png),
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'avatar-updated');

        $user->refresh();
        $this->assertNotNull($user->avatar_data, 'avatar_data nao foi salvo no banco');
        $this->assertNotNull($user->avatar_url);

        // A rota publica do avatar devolve a imagem.
        $this->get(route('avatar.show', $user))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');
    }
}
