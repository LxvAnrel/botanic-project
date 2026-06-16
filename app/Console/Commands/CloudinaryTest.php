<?php

namespace App\Console\Commands;

use Cloudinary\Cloudinary;
use Illuminate\Console\Command;

class CloudinaryTest extends Command
{
    protected $signature = 'cloudinary:test';
    protected $description = 'Testa a conexão com o Cloudinary e mostra o erro exato se falhar';

    public function handle()
    {
        $url = config('services.cloudinary.url');

        $this->info('CLOUDINARY_URL definido? ' . ($url ? 'sim' : 'NAO'));
        if ($url) {
            // Mostra o formato sem expor o secret
            $this->line('Formato: ' . preg_replace('/:.*@/', ':*****@', $url));
        } else {
            $this->error('Defina CLOUDINARY_URL nas variaveis (cloudinary://API_KEY:API_SECRET@CLOUD_NAME).');
            return self::FAILURE;
        }

        // PNG 1x1 em base64 (nao precisa de GD)
        $png = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M8AAAMBAQDJ/pLvAAAAAElFTkSuQmCC';

        try {
            $cloudinary = new Cloudinary($url);
            $result = $cloudinary->uploadApi()->upload($png, ['folder' => 'flora/test']);
            $this->info('✓ Upload OK!');
            $this->line('URL: ' . ($result['secure_url'] ?? '(sem url)'));
        } catch (\Throwable $e) {
            $this->error('✗ FALHOU: ' . $e->getMessage());
            $this->line(get_class($e));
        }

        return self::SUCCESS;
    }
}
