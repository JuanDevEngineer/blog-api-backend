<?php

declare(strict_types=1);

namespace App\Infrastructure\Storage;

class LocalFileStorage
{
    private string $uploadPath;
    private int    $maxSizeBytes  = 10_000_000;
    private array  $allowedMimes  = [
        'image/gif',
        'image/jpeg',
        'image/jpg',
        'image/png',
    ];

    public function __construct()
    {
        $this->uploadPath = $_ENV['UPLOAD_PATH'] ?? 'public/uploads/blogs/';
    }

    /**
     * @param  array  $file  Entrada de $_FILES['blog']
     * @return string        Ruta pública del archivo guardado
     * @throws \InvalidArgumentException  Tipo o tamaño inválido
     * @throws \RuntimeException          Error al mover el archivo
     */
    public function store(array $file): string
    {
        if (!in_array($file['type'], $this->allowedMimes, true)) {
            throw new \InvalidArgumentException(
                'Invalid file type. Allowed: jpg, jpeg, png, gif'
            );
        }

        if ($file['size'] > $this->maxSizeBytes) {
            throw new \InvalidArgumentException(
                'File size exceeds the 10 MB limit'
            );
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException(
                'Upload failed with error code: ' . $file['error']
            );
        }

        $extension  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename   = uniqid('blog_', true) . '.' . $extension;
        $targetPath = $this->uploadPath . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \RuntimeException('Could not save the uploaded file');
        }

        return $targetPath;
    }

    public function hasFile(array $files, string $key = 'blog'): bool
    {
        return isset($files[$key]['name']) && $files[$key]['name'] !== '';
    }
}
